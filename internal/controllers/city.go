package controllers

import (
	"encoding/json"
	"net/http"

	"github.com/zahra-pzk/tourist_site/internal/models"
	"github.com/zahra-pzk/tourist_site/pkg/database"
)

func GetAllCity(w http.ResponseWriter, r *http.Request) {
	var cities []models.City

	result := database.DB.Find(&cities)
	if result.Error != nil {
		http.Error(w, "خطا در دریافت شهرها", http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(http.StatusOK)
	json.NewEncoder(w).Encode(cities)
}
