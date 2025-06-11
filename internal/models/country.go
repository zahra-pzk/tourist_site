package models

type Country struct {
	ID            uint   `gorm:"primaryKey"`
	Name          string `gorm:"unique"`
	Iso3          string
	Iso2          string
	NumericCode   string
	PhoneCode     string
	Capital       string
	Currency      string
	CurrencyName  string
	CurrencySymbol string
	Tld           string
	Native        string
	Region        string
	Subregion     string
	Timezones     string
	Latitude      float64
	Longitude     float64
	Emoji         string
	EmojiU        string
}
