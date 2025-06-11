package database

import (
	"fmt"
	"os"

	"gorm.io/driver/postgres"
	"gorm.io/gorm"
	"github.com/joho/godotenv"
)

var DB *gorm.DB

func Connect() {
	err := godotenv.Load()
	if err != nil {
		panic("❌ فایل .env پیدا نشد.")
	}

	dsn := os.Getenv("DATABASE_URL")
	if dsn == "" {
		panic("❌ رشته اتصال DATABASE_URL تنظیم نشده است.")
	}

	db, err := gorm.Open(postgres.Open(dsn), &gorm.Config{})
	if err != nil {
		panic("❌ اتصال به دیتابیس برقرار نشد: " + err.Error())
	}

	DB = db
	fmt.Println("✅ اتصال به دیتابیس Neon برقرار شد.")
}
