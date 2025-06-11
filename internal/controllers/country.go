package controllers

import (
    "encoding/json"
    "net/http"

    "github.com/zahra-pzk/tourist_site/internal/models"
    "github.com/zahra-pzk/tourist_site/pkg/database"
)

func GetAllCountry(w http.ResponseWriter, r *http.Request) {
    var countries []models.Country

    err := database.DB.Preload("Cities.Places").Find(&countries).Error
    if err != nil {
        http.Error(w, "خطا در واکشی اطلاعات", http.StatusInternalServerError)
        return
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(countries)
}
