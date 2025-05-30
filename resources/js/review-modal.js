document.addEventListener("DOMContentLoaded", function () {
  const backdrop = document.getElementById("reviewBackdrop");
  const modal = document.getElementById("reviewModal");
  const closeBtn = document.getElementById("closeReviewModal");
  const reviewChoice = document.getElementById("reviewChoice");
  const reviewSteps = document.getElementById("reviewSteps");
  const openBtn = document.getElementById("openReviewModal");

  const steps = [];
  let currentStep = 0;
  let reviewData = {};

  // مرحله مقدمه
  const introStep = `
    <div class="text-center">
      <p class="mb-3">ممنونم که زمان کوتاهی رو در اختیار ما قرار میدی، تا هممون بتونیم از نظرت استفاده کنیم.</p>
      <p class="mb-4">نظرت برامون مهمه، پس لطفا اگر هر جزییات دیگه‌ای هست که دوست داری با ما درمیون بزاری، می‌تونی تو آخرین مرحله با عنوان "نکات اضافه" برامون بنویسی.</p>
      <button id="startSurvey" class="btn btn-primary">شروع</button>
    </div>
  `;

  const closeConfirm = `
    <div id="closeConfirm" class="position-fixed top-50 start-50 translate-middle bg-white rounded shadow p-3" style="z-index: 1060; width: 90%; max-width: 320px;">
      <p class="mb-3">مطمئنی که می‌خوای نظرسنجی رو لغو کنی؟ ثبت تجربه‌ت بهمون خیلی کمک می‌کنه :(</p>
      <div class="d-flex justify-content-between">
        <button id="cancelExit" class="btn btn-outline-secondary">نه، ادامه می‌دم</button>
        <button id="confirmExit" class="btn btn-danger">بله، خارج می‌شم</button>
      </div>
    </div>
  `;

  const questions = [
    {
      id: "travel_time",
      text: "چه زمانی به این مکان سفر کردید؟",
      options: [
        "فروردین سال جاری تا الان",
        "سال گذشته",
        "2 الی 5 سال قبل",
        "5 الی 10 سال قبل",
        "بیش از 10 سال پیش"
      ],
      type: "radio"
    },
    {
      id: "liked_points",
      text: "نکات مثبت این مکان برای شما چه چیزهایی بوده؟ (حداقل ۲ مورد انتخاب کنید)",
      options: ["منظره زیبا", "دسترسی آسان", "امکانات رفاهی خوب", "نظافت", "برخورد مناسب مردم", "سایر"],
      type: "checkbox",
      requireOtherDetails: true,
      minSelect: 2
    },
    {
      id: "disliked_points",
      text: "نکات منفی این مکان برای شما چه چیزهایی بوده؟ (حداقل ۲ مورد انتخاب کنید)",
      options: ["شلوغی زیاد", "گران بودن", "نبود امکانات کافی", "مشکل پارکینگ", "رفتار نامناسب کارکنان", "سایر"],
      type: "checkbox",
      requireOtherDetails: true,
      minSelect: 2
    },
    {
      id: "cost_level",
      text: "از نظر هزینه‌های مربوط به بازدید و استفاده از این مکان، وضعیت رو چطور می‌سنجید؟",
      options: ["رایگان", "خیلی ارزان", "ارزان", "متوسط", "گران", "خیلی گران"],
      type: "radio"
    },
    {
      id: "accessibility",
      text: "دسترسی به لوکیشن این مکان رو چطور می‌سنجید؟",
      options: ["خیلی راحت", "راحت", "متوسط", "سخت", "خیلی سخت"],
      type: "radio"
    },
    {
      id: "transportation",
      text: "برای سفر به این مکان از چه وسیله نقلیه‌ای استفاده کردید؟",
      options: ["خودروی شخصی", "اتوبوس", "قطار", "هواپیما", "سایر"],
      type: "radio",
      requireOtherDetails: true
    },
    {
      id: "season",
      text: "در چه فصلی سفر داشتید؟",
      options: ["بهار", "تابستان", "پاییز", "زمستان"],
      type: "radio"
    },
    {
      id: "weather",
      text: "آب‌و‌هوای این منطقه چطور بود؟",
      options: ["خیلی گرم", "گرم", "متعادل", "خنک", "سرد", "خیلی سرد"],
      type: "radio"
    },
    {
      id: "repeat_visit",
      text: "چند درصد احتمال بازدید دوباره از این مکان رو می‌دی؟",
      options: ["0-25", "25-50", "50-75", "75-100"],
      type: "radio"
    },
    {
        id: "rating",
        text: "تجربه کلی‌تون از این مکان رو با چند ستاره ارزیابی می‌کنید؟",
        type: "stars"
    },
    {
        id: "extra_comment",
        text: "اگر نکته یا تجربه‌ای هست که دوست داری با دیگران به اشتراک بذاری، اینجا بنویس.",
        type: "textarea"
      }
  ];

  function renderQuestion(stepIndex) {
    const q = questions[stepIndex];
    let html = `<p class="mb-3">${q.text}</p>`;

    if (q.type === "radio" || q.type === "checkbox") {
        q.options.forEach(opt => {
          const inputType = q.type;
          const id = `${q.id}_${opt}`.replace(/\s+/g, "_");
          html += `
            <div class="form-check">
              <input class="form-check-input" type="${inputType}" name="${q.id}" id="${id}" value="${opt}">
              <label class="form-check-label" for="${id}">${opt}</label>
            </div>`;
      
          // اضافه‌کردن input متنی برای گزینه "سایر"
          if (q.requireOtherDetails && opt === "سایر") {
            html += `
              <div class="form-group mt-2 d-none" id="${q.id}_other_wrapper">
                <input type="text" id="${q.id}_other" class="form-control" placeholder="لطفاً توضیح دهید...">
              </div>`;
          }
        });
      }
      
      if (q.type === "textarea") {
        html += `<textarea id="${q.id}" class="form-control" rows="4" placeholder="نظرت رو بنویس..."></textarea>`;
      }
      

      if (q.type === "stars") {
        html += `<div class="d-flex gap-1" id="starRating">`;
        for (let i = 1; i <= 10; i++) {
          html += `<i class="bi bi-star" data-star="${i}" style="font-size: 1.5rem; cursor: pointer; color: #f0ad4e;"></i>`;
        }
        html += `</div>`;
      }
      
      

    html += `<div class="mt-4 d-flex justify-content-between">
      ${stepIndex > 0 ? '<button class="btn btn-light" id="prevStep">قبلی</button>' : '<span></span>'}
      <button class="btn btn-primary" id="nextStep">${stepIndex === questions.length - 1 ? "ثبت نهایی" : "ثبت و بعدی"}</button>
    </div>`;
    if (q.requireOtherDetails) {
        const inputId = `${q.id}_other`;
        html += `
          <div class="form-group mt-2 d-none" id="${inputId}_wrapper">
            <label for="${inputId}">توضیح گزینه "سایر":</label>
            <input type="text" class="form-control" id="${inputId}" placeholder="مثلاً: ...">
          </div>
        `;
      };
      
    reviewSteps.innerHTML = html;

    if (q.requireOtherDetails) {
        const inputs = document.querySelectorAll(`input[name='${q.id}']`);
        inputs.forEach(input => {
          input.addEventListener('change', () => {
            const isOtherChecked = [...inputs].some(i => i.checked && i.value === 'سایر');
            const otherWrapper = document.getElementById(`${q.id}_other_wrapper`);
            if (otherWrapper) {
              if (isOtherChecked) otherWrapper.classList.remove('d-none');
              else {
                otherWrapper.classList.add('d-none');
                document.getElementById(`${q.id}_other`).value = '';
              }
            }
          });
        });
      }
      

    if (q.type === "stars") {
      document.querySelectorAll("#starRating i").forEach(star => {
        star.addEventListener("click", function () {
          const selected = parseInt(this.dataset.star);
          reviewData.rating = selected;
          document.querySelectorAll("#starRating i").forEach(s => {
            s.classList.remove("bi-star-fill");
            s.classList.add("bi-star");
          });
          for (let i = 0; i < selected; i++) {
            document.querySelectorAll("#starRating i")[i].classList.remove("bi-star");
            document.querySelectorAll("#starRating i")[i].classList.add("bi-star-fill");
          }
        });
      });
    }

    // دکمه‌ها
    document.getElementById("nextStep").addEventListener("click", function () {
      if (q.type === "checkbox") {
        const checked = [...document.querySelectorAll(`input[name='${q.id}']:checked`)];
        if (q.minSelect && checked.length < q.minSelect) return alert(`لطفاً حداقل ${q.minSelect} گزینه را انتخاب کنید.`);
        const isOther = checked.some(i => i.value === "سایر");
        const otherText = document.getElementById(`${q.id}_other`).value.trim();
        if (isOther && !otherText) return alert("لطفاً توضیح سایر را وارد کنید.");
        reviewData[q.id] = checked.map(i => i.value);
        if (isOther) reviewData[q.id + '_other'] = otherText;
    } else if (q.type === "radio") {
        const selected = document.querySelector(`input[name='${q.id}']:checked`);
        if (!selected) return alert("لطفاً یک گزینه را انتخاب کنید.");
        if (q.requireOtherDetails && selected.value === "سایر") {
          const detail = document.getElementById(`${q.id}_other`).value.trim();
          if (!detail) return alert("لطفاً توضیح سایر را وارد کنید.");
          reviewData[q.id + '_other'] = detail;
        }
        reviewData[q.id] = selected.value;
      
    } else if (q.type === "textarea") {
        const val = document.getElementById(q.id).value.trim();
        reviewData[q.id] = val;
      }
      
      function handleNext() {      
        // فقط اگر سوال فعلی از نوع ستاره‌ای بود، چک کنیم امتیاز وارد شده یا نه
        if (q.type === "stars" && !reviewData.rating) {
          return alert("لطفاً امتیاز خود را وارد کنید.");
        }
      
        // برو به مرحله بعد
        currentStep++;
        renderQuestion();
      }
      
      if (q.type === "stars") {
        const comment = document.getElementById("extra_comment")?.value.trim();
        if (comment) reviewData.extra_comment = comment;
      }{}
      

      currentStep++;
      if (currentStep >= questions.length) {
        reviewSteps.innerHTML = `
          <div class="text-center">
            <div class="mb-3">
              <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
              <h5 class="mt-2">از ثبت نظر شما متشکریم 🙏</h5>
            </div>
            <p class="mb-2">امتیاز شما در رابطه با این مکان:</p>
            <div class="d-flex justify-content-center gap-1 mb-3">
              ${[...Array(10)].map((_, i) => `
                <i class="bi ${i < reviewData.rating ? 'bi-star-fill' : 'bi-star'} text-warning"></i>
              `).join('')}
            </div>
            ${reviewData.extra_comment ? `
              <p class="mt-3"><strong>نکات اضافه:</strong><br>${reviewData.extra_comment}</p>
            ` : ''}
          </div>
        `;
        return;
      }
      
      renderQuestion(currentStep);
    });

    if (stepIndex > 0) {
      document.getElementById("prevStep").addEventListener("click", function () {
        currentStep--;
        renderQuestion(currentStep);
      });
    }
  }

  openBtn.addEventListener("click", () => {
    backdrop.classList.remove("d-none");
    modal.classList.remove("d-none");
    modal.classList.add("show");
    backdrop.classList.add("show");
    reviewChoice.classList.remove("d-none");
    reviewSteps.innerHTML = "";
  });

  closeBtn.addEventListener("click", () => {
    if (!reviewSteps.innerHTML.trim()) return closeModal();
    if (!document.getElementById('closeConfirm')) {
      modal.insertAdjacentHTML("beforeend", closeConfirm);
      document.getElementById("cancelExit").addEventListener("click", () => document.getElementById("closeConfirm").remove());
      document.getElementById("confirmExit").addEventListener("click", () => closeModal());
    }
  });

  function closeModal() {
    modal.classList.remove("show");
    backdrop.classList.remove("show");
    setTimeout(() => {
      modal.classList.add("d-none");
      backdrop.classList.add("d-none");
      document.getElementById("closeConfirm")?.remove();
      reviewChoice.classList.remove("d-none");
      reviewSteps.innerHTML = "";
      currentStep = 0;
    }, 300);
  }

  reviewChoice.querySelectorAll("button").forEach(btn => {
    btn.addEventListener("click", function () {
      reviewChoice.classList.add("d-none");
      reviewSteps.innerHTML = introStep;
      document.getElementById("startSurvey").addEventListener("click", () => renderQuestion(currentStep));
    });
  });
});
const starContainer = document.getElementById('starRating');
const stars = starContainer.querySelectorAll('i');

starContainer.addEventListener('mousemove', function(e) {
  const hoveredStar = e.target.closest('i');
  if (!hoveredStar) return;

  const index = Array.from(stars).indexOf(hoveredStar);
  
  stars.forEach((star, i) => {
    if (i <= index) {
      star.classList.add('hovered');
    } else {
      star.classList.remove('hovered');
    }
  });
});

starContainer.addEventListener('mouseleave', function() {
  stars.forEach(star => {
    star.classList.remove('hovered');
  });
});

stars.forEach((star, index) => {
  star.addEventListener('click', function() {
    stars.forEach((s, i) => {
      if (i <= index) {
        s.classList.remove('bi-star');
        s.classList.add('bi-star-fill');
      } else {
        s.classList.remove('bi-star-fill');
        s.classList.add('bi-star');
      }
    });
  });
});
document.querySelectorAll('.form-check').forEach(check => {
    check.addEventListener('click', function() {
      const input = this.querySelector('.form-check-input');
      input.checked = !input.checked;
      
      // افکت ویژوال
      this.classList.add('selected');
      setTimeout(() => {
        this.classList.remove('selected');
      }, 300);
    });
  });
  function advancedStepTransition(currentStep, nextStep) {
    const currentStepElement = document.querySelector('.question-step');
    
    // خروج مرحله فعلی
    currentStepElement.classList.add('step-transition-exit');
    
    setTimeout(() => {
      // حذف مرحله قبلی
      currentStepElement.remove();
      
      // ایجاد مرحله جدید
      const newStepElement = createStepElement(nextStep);
      newStepElement.classList.add('step-transition-enter');
      
      // اضافه کردن مرحله جدید
      document.getElementById('reviewSteps').appendChild(newStepElement);
      
      // اجرای انیمیشن ورود
      setTimeout(() => {
        newStepElement.classList.remove('step-transition-enter');
      }, 50);
    }, 500);
  }
  
  function createStepElement(step) {
    const stepElement = document.createElement('div');
    stepElement.classList.add('question-step');
    
    // منطق ایجاد محتوای مرحله جدید
    // ...
    
    return stepElement;
  }
  function enhancedInteractions() {
    const interactiveElements = document.querySelectorAll(
      'button, .form-check, #starRating i'
    );
  
    interactiveElements.forEach(el => {
      el.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
        this.style.transition = 'transform 0.3s ease';
      });
  
      el.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
      });
    });
  }
  
  // افکت صدا برای کلیک
  function addClickSounds() {
    const clickSounds = [
      new Audio('click1.mp3'),
      new Audio('click2.mp3'),
      new Audio('click3.mp3')
    ];
  
    document.addEventListener('click', function(e) {
      if (e.target.matches('button, .form-check, #starRating i')) {
        const sound = clickSounds[
          Math.floor(Math.random() * clickSounds.length)
        ];
        sound.play();
      }
    });
  }
  function addTactileAndAudioFeedback() {
    const interactiveElements = document.querySelectorAll(
      'button, .form-check, #starRating i'
    );
  
    const hapticSupport = 'vibrate' in navigator;
    const audioSupport = !!Audio;
  
    const clickSounds = [
      new Audio('click1.mp3'),
      new Audio('click2.mp3'),
      new Audio('click3.mp3')
    ];
  
    interactiveElements.forEach(el => {
      el.addEventListener('click', function() {
        // Haptic Feedback
        if (hapticSupport) {
          navigator.vibrate(30);
        }
  
        // Audio Feedback
        if (audioSupport) {
          const sound = clickSounds[
            Math.floor(Math.random() * clickSounds.length)
          ];
          sound.currentTime = 0;
          sound.play();
        }
      });
    });
  }
  function advancedValidation(questions) {
    return questions.map(question => {
      return {
        ...question,
        validate: function(value) {
          switch(question.type) {
            case 'checkbox':
              return value.length >= (question.minSelect || 1);
            case 'radio':
              return !!value;
            case 'stars':
              return value > 0;
            case 'textarea':
              return value.trim().length > 10;
            default:
              return true;
          }
        },
        errorMessage: function() {
          switch(question.type) {
            case 'checkbox':
              return `لطفاً حداقل ${question.minSelect || 1} گزینه را انتخاب کنید.`;
            case 'radio':
              return 'لطفاً یک گزینه را انتخاب کنید.';
            case 'stars':
              return 'لطفاً امتیاز خود را مشخص کنید.';
            case 'textarea':
              return 'توضیحات باید حداقل 10 کاراکتر داشته باشند.';
            default:
              return 'لطفاً این قسمت را پر کنید.';
          }
        }
      };
    });
  }
  class SmartFeedbackSystem {
    constructor(reviewData) {
      this.reviewData = reviewData;
    }
  
    generatePersonalizedFeedback() {
      const feedbackRules = {
        rating: {
          1: 'متوجه هستیم که تجربه خوبی نداشتید.',
          2: 'ما برای بهبود تلاش می‌کنیم.',
          3: 'با تشکر از نظر شما، تلاش می‌کنیم بهتر شویم.',
          4: 'خوشحالیم که تجربه نسبتاً خوبی داشتید.',
          5: 'از اینکه رضایت شما را جلب کردیم خوشحالیم!'
        },
        travelTime: {
          'فروردین سال جاری تا الان': 'تجربه شما بسیار تازه است.',
          'سال گذشته': 'از اینکه سال گذشته به اینجا سفر کردید متشکریم.',
          // سایر موارد...
        }
      };
  
      return {
        ratingFeedback: feedbackRules.rating[this.reviewData.rating] || '',
        travelTimeFeedback: feedbackRules.travelTime[this.reviewData.travel_time] || ''
      };
    }
  
    recommendNextSteps() {
      const recommendations = {
        lowRating: [
          'بررسی دقیق نکات منفی',
          'تماس با واحد پشتیبانی',
          'ارائه جبران خسارت'
        ],
        highRating: [
          'دعوت به سفرهای بعدی',
          'اشتراک‌گذاری تجربه',
          'ارائه تخفیف‌های ویژه'
        ]
      };
  
      return this.reviewData.rating <= 2 
        ? recommendations.lowRating 
        : recommendations.highRating;
    }
  }
  class ThemeManager {
    constructor() {
      this.currentTheme = localStorage.getItem('theme') || 'light';
      this.initTheme();
    }
  
    initTheme() {
      document.documentElement.setAttribute('data-theme', this.currentTheme);
      this.updateThemeColors();
    }
  
    updateThemeColors() {
      const root = document.documentElement;
      const themeColors = {
        light: {
          background: '#f5f7fa',
          text: '#333',
          primary: '#0d6efd',
          secondary: '#6c757d',
          modalBackground: 'rgba(255,255,255,0.9)',
          shadowColor: 'rgba(0,0,0,0.1)'
        },
        dark: {
          background: '#121212',
          text: '#e0e0e0',
          primary: '#4f89ff',
          secondary: '#9c27b0',
          modalBackground: 'rgba(30,30,30,0.95)',
          shadowColor: 'rgba(255,255,255,0.1)'
        }
      };
  
      const theme = themeColors[this.currentTheme];
      
      Object.entries(theme).forEach(([key, value]) => {
        root.style.setProperty(`--${key}`, value);
      });
    }
  
    toggleTheme() {
      this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
      localStorage.setItem('theme', this.currentTheme);
      this.initTheme();
      this.applyTransition();
    }
  
    applyTransition() {
      document.documentElement.classList.add('theme-transition');
      setTimeout(() => {
        document.documentElement.classList.remove('theme-transition');
      }, 500);
    }
  
    // متد برای تشخیص خودکار تم
    detectSystemTheme() {
      const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');
      this.currentTheme = systemTheme.matches ? 'dark' : 'light';
      this.initTheme();
  
      systemTheme.addEventListener('change', (e) => {
        this.currentTheme = e.matches ? 'dark' : 'light';
        this.initTheme();
      });
    }
  }
  
  function validateStep(step) {
    const requiredFields = step.querySelectorAll('[required]');
    requiredFields.forEach(field => {
      field.addEventListener('invalid', function() {
        this.classList.add('is-invalid');
        this.style.animation = 'shake 0.5s';
      });
  
      field.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        this.style.animation = '';
      });
    });
  }
    const themeStyles = `
    :root {
      --transition-speed: 0.5s;
    }
  
    html.theme-transition,
    html.theme-transition *,
    html.theme-transition *::before,
    html.theme-transition *::after {
      transition: 
        color var(--transition-speed) ease,
        background-color var(--transition-speed) ease,
        background var(--transition-speed) ease,
        border-color var(--transition-speed) ease,
        box-shadow var(--transition-speed) ease;
    }
  
    [data-theme='dark'] {
      color-scheme: dark;
      background-color: var(--background);
      color: var(--text);
    }
  
    #themeToggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1100;
      background: var(--primary);
      color: white;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: all 0.3s ease;
    }
  
    #themeToggle:hover {
      transform: rotate(180deg) scale(1.1);
    }
  `;
    

// راه‌اندازی مدیریت تم
document.addEventListener('DOMContentLoaded', () => {
// اضافه کردن استایل‌های تم به سند
const styleElement = document.createElement('style');
styleElement.textContent = themeStyles;
document.head.appendChild(styleElement);

// ایجاد دکمه تغییر تم
const themeToggle = document.createElement('button');
themeToggle.id = 'themeToggle';
themeToggle.innerHTML = `
<i class="fas ${
localStorage.getItem('theme') === 'dark' ? 'fa-moon' : 'fa-sun'
}"></i>
`;
document.body.appendChild(themeToggle);

// مدیریت تم
const themeManager = new ThemeManager();

// تشخیص خودکار تم سیستم
themeManager.detectSystemTheme();

// رویداد کلیک برای تغییر تم
themeToggle.addEventListener('click', () => {
themeManager.toggleTheme();

// تغییر آیکون
themeToggle.innerHTML = `
<i class="fas ${
  themeManager.currentTheme === 'dark' ? 'fa-moon' : 'fa-sun'
}"></i>
`;
});
});

// مدیریت پیشرفته برای تم
class AdvancedThemeManager extends ThemeManager {
constructor() {
super();
this.themePreferences = {
darkMode: {
  contrastLevel: 'high',
  reduceMotion: false
}
};
}

applyAccessibilitySettings() {
const root = document.documentElement;

if (this.themePreferences.darkMode.contrastLevel === 'high') {
root.style.setProperty('--contrast', '1.5');
}

if (this.themePreferences.darkMode.reduceMotion) {
root.style.setProperty('--transition-speed', '0.1s');
}
}

// متدهای اضافی برای مدیریت تنظیمات دسترسی پذیری
setContrastLevel(level) {
this.themePreferences.darkMode.contrastLevel = level;
this.applyAccessibilitySettings();
}

toggleReduceMotion() {
this.themePreferences.darkMode.reduceMotion = 
!this.themePreferences.darkMode.reduceMotion;
this.applyAccessibilitySettings();
}
}


// متدهای اضافی یا کلاس‌های مرتبط
function initializeAdvancedThemeSystem() {
  const advancedThemeManager = new AdvancedThemeManager();
  
  // تنظیمات اضافی
  const accessibilityToggle = document.getElementById('accessibilitySettings');
  
  if (accessibilityToggle) {
    accessibilityToggle.addEventListener('change', (event) => {
      if (event.target.id === 'highContrastMode') {
        advancedThemeManager.setContrastLevel(
          event.target.checked ? 'high' : 'normal'
        );
      }
      
      if (event.target.id === 'reduceMotionToggle') {
        advancedThemeManager.toggleReduceMotion();
      }
    });
  }
}

// اجرای سیستم تم پیشرفته
document.addEventListener('DOMContentLoaded', initializeAdvancedThemeSystem);


