package importer

import (
	"context"
	"encoding/csv"
	"fmt"
	"os"
	"strings"

	"github.com/jackc/pgx/v5"
	"github.com/zahra-pzk/tourist_site/pkg/translate"
)

func ImportCSVToPostgres(connStr string, csvPath string, table string, translateColumns []string) error {
	ctx := context.Background()

	conn, err := pgx.Connect(ctx, connStr)
	if err != nil {
		return fmt.Errorf("خطا در اتصال به دیتابیس: %w", err)
	}
	defer conn.Close(ctx)

	file, err := os.Open(csvPath)
	if err != nil {
		return fmt.Errorf("خطا در باز کردن فایل CSV: %w", err)
	}
	defer file.Close()

	reader := csv.NewReader(file)
	reader.TrimLeadingSpace = true
	records, err := reader.ReadAll()
	if err != nil {
		return fmt.Errorf("خطا در خواندن CSV: %w", err)
	}

	if len(records) < 2 {
		return fmt.Errorf("فایل CSV خالی است یا فقط هدر دارد")
	}

	columns := records[0]
	data := records[1:]

	tx, err := conn.Begin(ctx)
	if err != nil {
		return fmt.Errorf("خطا در شروع تراکنش: %w", err)
	}
	defer tx.Rollback(ctx)

	insertSQL := fmt.Sprintf("INSERT INTO %s (%s) VALUES ", table, strings.Join(columns, ","))
	values := []interface{}{}
	paramIndex := 1
	valueStrings := []string{}

	for _, row := range data {
		vals := []string{}
		for i, col := range row {
			// ترجمه خودکار
			if contains(translateColumns, columns[i]) {
				col, _ = translate.AutoTranslate(col) // فرض: توابع گوگل ترنسلیت در translate.go
			}
			values = append(values, col)
			vals = append(vals, fmt.Sprintf("$%d", paramIndex))
			paramIndex++
		}
		valueStrings = append(valueStrings, "("+strings.Join(vals, ",")+")")
	}

	insertSQL += strings.Join(valueStrings, ",")
	_, err = tx.Exec(ctx, insertSQL, values...)
	if err != nil {
		return fmt.Errorf("خطا در اجرای INSERT: %w", err)
	}

	err = tx.Commit(ctx)
	if err != nil {
		return fmt.Errorf("خطا در Commit: %w", err)
	}

	fmt.Println("✅ داده‌ها با موفقیت وارد شدند.")
	return nil
}

func contains(slice []string, s string) bool {
	for _, v := range slice {
		if v == s {
			return true
		}
	}
	return false
}
