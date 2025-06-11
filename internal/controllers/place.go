package controllers

import (
    "encoding/json"
    "net/http"
    "strconv"

    "github.com/zahra-pzk/tourist_site/internal/models"
    "github.com/zahra-pzk/tourist_site/pkg/database"
)

func GetAllPlaces(w http.ResponseWriter, r *http.Request) {
    var places []models.Place
    database.DB.Find(&places)
    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(places)
}

func CreatePlace(w http.ResponseWriter, r *http.Request) {
    var place models.Place
    err := json.NewDecoder(r.Body).Decode(&place)
    if err != nil {
        http.Error(w, "Invalid input", http.StatusBadRequest)
        return
    }

    result := database.DB.Create(&place)
    if result.Error != nil {
        http.Error(w, result.Error.Error(), http.StatusInternalServerError)
        return
    }

    w.WriteHeader(http.StatusCreated)
    json.NewEncoder(w).Encode(place)
}

func GetPlace(w http.ResponseWriter, r *http.Request) {
    idStr := r.URL.Path[len("/api/places/"):]
    id, err := strconv.Atoi(idStr)
    if err != nil {
        http.Error(w, "Invalid ID", http.StatusBadRequest)
        return
    }

    var place models.Place
    result := database.DB.First(&place, id)
    if result.Error != nil {
        http.NotFound(w, r)
        return
    }

    json.NewEncoder(w).Encode(place)
}
