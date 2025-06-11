package main

import (
	"log"
	"os"

	"github.com/joho/godotenv"
	"github.com/zahra-pzk/tourist_site/internal/importer"
)

func main() {
	err := godotenv.Load()
	if err != nil {
		log.Fatal("❌ فایل env پیدا نشد.")
	}
	connStr := os.Getenv("DATABASE_URL")
	if connStr == "" {
		log.Fatal("❌ DATABASE_URL در .env تنظیم نشده.")
	}

	err = importer.ImportCSVToPostgres(connStr, "Book1.csv", "places", []string{"attraction_name", "description"})
	if err != nil {
		log.Fatalf("❌ خطا در وارد کردن داده‌ها: %v", err)
	}
}
