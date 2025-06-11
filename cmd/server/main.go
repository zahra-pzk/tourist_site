package main

import (
	"context"
	"database/sql"
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"os"

	_ "github.com/jackc/pgx/v5/stdlib"
	"github.com/joho/godotenv"
	"github.com/zahra-pzk/tourist_site/internal/controllers"
	"github.com/zahra-pzk/tourist_site/internal/models"
	"github.com/zahra-pzk/tourist_site/pkg/database"
	"golang.org/x/oauth2"
	"golang.org/x/oauth2/google"
)

var googleOauthConfig *oauth2.Config
var oauthStateString = "random-state-value-123" 

func initOAuth() {
	googleOauthConfig = &oauth2.Config{
		ClientID:     os.Getenv("GOOGLE_CLIENT_ID"),
		ClientSecret: os.Getenv("GOOGLE_CLIENT_SECRET"),
		RedirectURL:  os.Getenv("GOOGLE_REDIRECT_URL"),
		Scopes: []string{
			"https://www.googleapis.com/auth/userinfo.email",
			"https://www.googleapis.com/auth/userinfo.profile",
		},
		Endpoint: google.Endpoint,
	}
}

func main() {
	if err := godotenv.Load(); err != nil {
		log.Println("⚠️ فایل .env پیدا نشد.")
	}

	initOAuth()

	connStr := os.Getenv("DATABASE_URL")
	if connStr == "" {
		log.Fatal("❌ متغیر DATABASE_URL تنظیم نشده.")
	}
	db, err := sql.Open("pgx", connStr)
	if err != nil {
		log.Fatalf("❌ خطا در اتصال: %v", err)
	}
	defer db.Close()

	ctx := context.Background()
	if err := db.PingContext(ctx); err != nil {
		log.Fatalf("❌ اتصال به دیتابیس برقرار نشد: %v", err)
	}
	fmt.Println("✅ اتصال موفق به دیتابیس Neon")

	var version string
	if err := db.QueryRowContext(ctx, "SELECT version()").Scan(&version); err != nil {
		log.Fatalf("❌ دریافت نسخه دیتابیس: %v", err)
	}
	fmt.Println("📦 نسخه دیتابیس:", version)

	database.Connect()
	database.DB.AutoMigrate(&models.User{}, &models.Country{}, &models.City{}, &models.Place{})

	mux := http.NewServeMux()

	mux.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
		http.ServeFile(w, r, "public/index.html")
	})
	mux.HandleFunc("/signup", func(w http.ResponseWriter, r *http.Request) {
		http.ServeFile(w, r, "public/signup.html")
	})

	mux.HandleFunc("/auth/google", handleGoogleLogin)
	mux.HandleFunc("/auth/google/callback", handleGoogleCallback)

	mux.HandleFunc("/api/register", controllers.RegisterUser)
	mux.HandleFunc("/api/countries", controllers.GetAllCountry)
	mux.HandleFunc("/api/city", controllers.GetAllCity)
	mux.HandleFunc("/api/places", func(w http.ResponseWriter, r *http.Request) {
		switch r.Method {
		case http.MethodGet:
			controllers.GetAllPlaces(w, r)
		case http.MethodPost:
			controllers.CreatePlace(w, r)
		default:
			http.Error(w, "Method not allowed", http.StatusMethodNotAllowed)
		}
	})
	mux.HandleFunc("/api/places/", controllers.GetPlace)

	fs := http.FileServer(http.Dir("public"))
	mux.Handle("/static/", http.StripPrefix("/static/", fs))

	fmt.Println("🚀 سرور در حال اجرا روی http://localhost:8080")
	if err := http.ListenAndServe(":8080", mux); err != nil {
		log.Fatalf("❌ خطا در اجرای سرور: %v", err)
	}
}

func handleGoogleLogin(w http.ResponseWriter, r *http.Request) {
	url := googleOauthConfig.AuthCodeURL(oauthStateString)
	http.Redirect(w, r, url, http.StatusTemporaryRedirect)
}

func handleGoogleCallback(w http.ResponseWriter, r *http.Request) {
	state := r.URL.Query().Get("state")
	if state != oauthStateString {
		http.Redirect(w, r, "/", http.StatusTemporaryRedirect)
		return
	}

	code := r.URL.Query().Get("code")
	if code == "" {
		http.Redirect(w, r, "/", http.StatusTemporaryRedirect)
		return
	}

	token, err := googleOauthConfig.Exchange(context.Background(), code)
	if err != nil {
		http.Redirect(w, r, "/", http.StatusTemporaryRedirect)
		return
	}

	client := googleOauthConfig.Client(context.Background(), token)
	resp, err := client.Get("https://www.googleapis.com/oauth2/v2/userinfo")
	if err != nil {
		http.Redirect(w, r, "/", http.StatusTemporaryRedirect)
		return
	}
	defer resp.Body.Close()

	var userInfo map[string]interface{}
	if err := json.NewDecoder(resp.Body).Decode(&userInfo); err != nil {
		http.Redirect(w, r, "/", http.StatusTemporaryRedirect)
		return
	}

	fmt.Fprintf(w, "اطلاعات کاربر:\n%v\n", userInfo)
}
