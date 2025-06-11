package importer

import (
	"context"
	"encoding/csv"
	"fmt"
	"log"
	"os"
	"strings"

	"github.com/jackc/pgx/v5"
)

func importCsv(connStr, tableName, filePath string, columns []string) error {
	ctx := context.Background()

	conn, err := pgx.Connect(ctx, connStr)
	if err != nil {
		return fmt.Errorf("❌ اتصال به دیتابیس ناموفق: %v", err)
	}
	defer conn.Close(ctx)

	// باز کردن فایل CSV
	file, err := os.Open(filePath)
	if err != nil {
		return fmt.Errorf("❌ باز کردن فایل: %v", err)
	}
	defer file.Close()

	reader := csv.NewReader(file)
	records, err := reader.ReadAll()
	if err != nil {
		return fmt.Errorf("❌ خواندن CSV: %v", err)
	}

	if len(records) < 1 {
		return fmt.Errorf("❌ CSV فاقد داده است")
	}

	// حذف عنوان ستون
	records = records[1:]

	// آماده‌سازی برای COPY
	copySource := pgx.CopyFromRows(records)
	copyCount, err := conn.CopyFrom(ctx, pgx.Identifier{tableName}, columns, copySource)
	if err != nil {
		return fmt.Errorf("❌ عملیات COPY شکست خورد: %v", err)
	}

	log.Printf("✅ %d ردیف در جدول %s درج شد.\n", copyCount, tableName)
	return nil
}
