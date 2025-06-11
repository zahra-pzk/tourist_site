package models

type City struct {
	ID           uint    `gorm:"primaryKey"`
	Name         string
	ProvinceID   uint
	Province     string
	CountryID    uint
	CountryCode  string
	Latitude     float64
	Longitude    float64
	WikiDataId   string
}
