# 🌍 Tourist Site | Laravel RESTful API

پروژه‌ی معرفی مکان‌های دیدنی با ساختار کشور > استان > شهر > مکان دیدنی  
این پروژه در قالب تمرین مهندسی نرم‌افزار با استفاده از **Laravel 10** توسعه یافته و امکان نمایش، ثبت‌نام، نظردهی و استفاده از Google Street View را فراهم می‌کند.

---

## 🌐 Front-End

فرانت‌اند این پروژه توسط تیم فرانت‌اند با استفاده از تکنولوژی‌های زیر پیاده‌سازی شده است:

- HTML5  
- CSS3  
- JavaScript (Vanilla JS)  
- انیمیشن‌ها و ترنزیشن‌های سفارشی  

تمام تعاملات و طراحی‌های UI به‌صورت دستی و برای تجربه‌ای روان و دل‌نشین ساخته شده‌اند.

---

## 📥 منابع داده و محتوا

بخشی از داده‌ها و محتوا (مانند توضیحات، تصاویر یا امتیازهای مکان‌ها) از منابع عمومی جمع‌آوری شده تا کیفیت و غنای محتوا افزایش یابد.  
برخی از این منابع عبارت‌اند از:

- [Wikipedia](https://www.wikipedia.org)  
- [pexels](https://www.pexels.com/)  
- [vecteezy](https://www.vecteezy.com/)  
- [kaggle](http://kaggle.com/)  
- و سایر منابع معتبر...  

از فضای باز این پلتفرم‌ها سپاس‌گزاریم و به شرایط استفاده آن‌ها پایبندیم. در صورتی که مالک هر محتوایی هستید و فکر می‌کنید ارجاع مناسب ذکر نشده، خواهشمندیم جهت اصلاح با ما در تماس باشید.

---

## 🤝 اعتبارها و مشارکت‌کنندگان

این پروژه به‌عنوان بخشی از یک پروژه دانشگاهی مهندسی نرم‌افزار توسط تیمی متشکل از ۳ دانشجو توسعه یافته است.  
- بخش Front-End توسط تیم فرانت‌اند  
- بخش Back-End توسط تیم بک‌اند  
- هماهنگی و مستندسازی توسط مدیر پروژه  

---

## 📌 امکانات پروژه

- **ثبت و مدیریت کاربران** (احراز هویت با Laravel Sanctum)  
- **ساختار سلسله‌مراتبی**: کشور → شهر → مکان دیدنی  
- **ثبت تجربه سفر** و نظردهی مرحله‌ای و حرفه‌ای  
- **اتصال به Google Street View** برای هر مکان  
- **API RESTful کامل** جهت استفاده در فرانت‌اند SPA  
- **بارگذاری و ذخیره داده‌ها** از دیتاست‌های Kaggle با Seeder  

---

## 🧱 تکنولوژی‌های استفاده‌شده

| بخش            | ابزار                                                                           |
|----------------|---------------------------------------------------------------------------------|
| زبان بک‌اند    | PHP (Laravel 10)                                                                |
| دیتابیس        | MySQL                                                                           |
| احراز هویت     | Laravel Sanctum                                                                 |
| API            | Laravel Resource Routes (Controllers + API Resources)                           |
| مدیریت داده‌ها | Laravel Seeder + CSV Parsing                                                    |
| پکیج‌های JS    | Vite (for asset bundling)                                                       |
| UI / CSS       | TailwindCSS (در صورت نیاز)                                          |
| سیستم کنترل ورژن | Git + GitHub                                                                   |
| مدیریت پروژه   | Jira                                                                            |

---

## نصب و راه‌اندازی

1. **کلون کردن مخزن**  
   ```bash
   git clone https://github.com/zahra-pzk/tourist_site.git
   cd tourist_site
````

2. **نصب پکیج‌های PHP**

   ```bash
   composer install
   ```

3. **ایجاد و تنظیم فایل `.env`**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   سپس در فایل `.env` اطلاعات دیتابیس (DB\_CONNECTION، DB\_HOST، DB\_PORT، DB\_DATABASE، DB\_USERNAME، DB\_PASSWORD) را وارد کنید.

4. **اجرای مایگریشن‌ها و سیدها**
   ابتدا دیتابیس خالی بسازید (مثلاً با نام `tourist_site`).
   سپس:

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

   > توجه: اگر فایل‌های CSV‌ در مسیر `database/seeders/data/` قرار دارند، اطمینان حاصل کنید نام و ساختار فولدرها صحیح باشند.

5. **نصب و بیلد پکیج‌های JavaScript (Vite)**

   ```bash
   npm install
   npm run build
   ```

6. **اجرای سرور لوکال**

   ```bash
   php artisan serve
   ```

   پس از اجرا، پروژه در آدرس `http://127.0.0.1:8000` در دسترس خواهد بود.

---

## 🗂 مسیرهای مهم API

برای استفاده‌ی فرانت‌اند از سرویس‌های بک‌اند، مسیرهای زیر در دسترس هستند:

| Route                                    | Method | توضیحات                                                                                                                                                        |
| ---------------------------------------- | ------ | -------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `/api/register`                          | POST   | ثبت‌نام کاربر (ارسال: name, email, password, password\_confirmation)                                                                                           |
| `/api/login`                             | POST   | ورود کاربر (ارسال: email, password)                                                                                                                            |
| `/api/logout`                            | POST   | خروج از سیستم (توکن در Header: `Authorization: Bearer {token}`)                                                                                                |
| `/api/profile`                           | GET    | دریافت اطلاعات کاربر (توکن لازم)                                                                                                                               |
| `/api/countries`                         | GET    | دریافت لیست تمامی کشورها                                                                                                                                       |
| `/api/countries`                         | POST   | اضافه کردن کشور جدید (ارسال: name)                                                                                                                             |
| `/api/countries/{country}`               | GET    | نمایش جزئیات یک کشور                                                                                                                                           |
| `/api/countries/{country}`               | PUT    | ویرایش یک کشور (ارسال: name)                                                                                                                                   |
| `/api/countries/{country}`               | DELETE | حذف یک کشور                                                                                                                                                    |
| `/api/provinces`                         | GET    | دریافت لیست تمامی استان‌ها (با رابطه Country)                                                                                                                  |
| `/api/provinces`                         | POST   | اضافه کردن استان جدید (ارسال: name, country\_id)                                                                                                               |
| `/api/provinces/{province}`              | GET    | نمایش جزئیات یک استان (با رابطه Country)                                                                                                                       |
| `/api/provinces/{province}`              | PUT    | ویرایش یک استان (ارسال: name, country\_id)                                                                                                                     |
| `/api/provinces/{province}`              | DELETE | حذف یک استان                                                                                                                                                   |
| `/api/places`                            | GET    | دریافت تمامی مکان‌های دیدنی (با روابط Province → Country و امتیاز متوسط)                                                                                       |
| `/api/places`                            | POST   | اضافه کردن مکان جدید (ارسال: name, description, province\_id, image\_url?, google\_street\_view\_url?)                                                         |
| `/api/places/{place}`                    | GET    | نمایش جزئیات یک مکان (با Province → Country، تجارب سفر و امتیاز متوسط)                                                                                         |
| `/api/places/{place}`                    | PUT    | ویرایش مکان (ارسال: name, description, province\_id, image\_url?, google\_street\_view\_url?)                                                                  |
| `/api/places/{place}`                    | DELETE | حذف مکان                                                                                                                                                       |
| `/api/places/{place}/street-view`        | GET    | دریافت URL نمای خیابانی ثبت‌شده برای مکان                                                                                                                      |
| `/api/travel-experiences`                | POST   | ثبت تجربه سفر جدید توسط کاربر (ارسال: place\_id, has\_traveled, travel\_date?, positive\_points?, negative\_points?, suitable\_for?, rating?, extra\_comment?) |
| `/api/places/{place}/travel-experiences` | GET    | دریافت تجربیات ثبت‌شده برای یک مکان (نمایش کاربر و امتیاز)                                                                                                     |

---

## ⚖️ مجوز (License)

This project is open-source under the MIT license.  
MIT © 2025 Zahra Pazoki  

شما آزادید این پروژه را برای مقاصد شخصی یا تجاری استفاده، تغییر و توزیع کنید. لطفاً متن زیر از مجوز MIT را حفظ نمایید:  

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “Software”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
