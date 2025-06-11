package models

type Place struct {
	ID          uint   `gorm:"primaryKey"`
	Name        string
	Category    string
	Description string
	CityID      uint
}
