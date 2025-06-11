package importer

import (
	"encoding/csv"
	"log"
	"os"
	"strconv"

	"github.com/zahra-pzk/tourist_site/internal/models"
	"github.com/zahra-pzk/tourist_site/pkg/database"
)

// بارگذاری کشورها
func LoadCountriesCSV(path string) {
	file, err := os.Open(path)
	if err != nil {
		log.Fatalf("خطا در باز کردن فایل کشورها: %v", err)
	}
	defer file.Close()

	reader := csv.NewReader(file)
	records, err := reader.ReadAll()
	if err != nil {
		log.Fatalf("خطا در خواندن فایل کشورها: %v", err)
	}

	for i, row := range records {
		if i == 0 {
			continue // ردیف عنوان
		}
		country := models.Country{
			ID:     parseInt(row[0]),
			Name:   row[1], // "country"
		}
		database.DB.Create(&country)
	}
}

// بارگذاری شهرها
func LoadCitiesCSV(path string) {
	file, err := os.Open(path)
	if err != nil {
		log.Fatalf("خطا در باز کردن فایل شهرها: %v", err)
	}
	defer file.Close()

	reader := csv.NewReader(file)
	records, err := reader.ReadAll()
	if err != nil {
		log.Fatalf("خطا در خواندن فایل شهرها: %v", err)
	}

	for i, row := range records {
		if i == 0 {
			continue
		}
		city := models.City{
			ID:        parseInt(row[0]),
			Name:      row[1],
			CountryID: parseInt(row[5]), // country_id
		}
		database.DB.Create(&city)
	}
}

// بارگذاری مکان‌های دیدنی
func LoadPlacesCSV(path string) {
	file, err := os.Open(path)
	if err != nil {
		log.Fatalf("خطا در باز کردن فایل مکان‌ها: %v", err)
	}
	defer file.Close()

	reader := csv.NewReader(file)
	records, err := reader.ReadAll()
	if err != nil {
		log.Fatalf("خطا در خواندن فایل مکان‌ها: %v", err)
	}

	for i, row := range records {
		if i == 0 {
			continue
		}
		place := models.Place{
			ID:          parseInt(row[0]),
			Name:        row[1],
			Category:    row[2],
			Description: row[3],
			CityID:      parseInt(row[4]),
		}
		database.DB.Create(&place)
	}
}

func parseInt(s string) uint {
	val, err := strconv.Atoi(s)
	if err != nil {
		return 0
	}
	return uint(val)
}
