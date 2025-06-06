@once
  @push('styles')
    <style>
      #reviewBackdrop { z-index: 1040; }
      #reviewModal   { z-index: 1050; }

      #reviewBackdrop.show {
        animation: fadeIn 0.3s forwards;
      }
      #reviewModal.show {
        animation: popIn 0.4s forwards;
      }

      @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
      }
      @keyframes popIn {
        from { transform: translate(-50%, -50%) scale(0.8) rotateX(90deg); opacity: 0; }
        to   { transform: translate(-50%, -50%) scale(1) rotateX(0); opacity: 1; }
      }
    </style>
  @endpush
@endonce

<div id="reviewBackdrop" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none"></div>

<div id="reviewModal" class="position-fixed top-50 start-50 translate-middle bg-white rounded-3 shadow-lg p-4 d-none"
     style="width:90%;max-width:400px;perspective:1000px;">
  <button id="closeReviewModal" class="btn-close position-absolute top-2 end-2" aria-label="Close"></button>

  <div id="reviewChoice" class="d-flex flex-column gap-3">
    <button class="btn btn-outline-secondary fw-semibold" data-choice="comment">ثبت نظر شخصی</button>
    <button class="btn btn-outline-secondary fw-semibold" data-choice="experience">ثبت تجربه سفر</button>
  </div>

  <div id="reviewSteps" class="mt-4"></div>
</div>

<div id="exitConfirm" class="position-fixed top-50 start-50 translate-middle bg-white p-3 rounded shadow-lg d-none"
     style="z-index: 1060; max-width: 350px; width: 90%;">
  <p class="mb-3">❗ مطمئنی که می‌خوای نظرسنجی رو لغو کنی؟ ثبت تجربه‌ت بهمون خیلی کمک میکنه :(</p>
  <div class="d-flex justify-content-end gap-2">
    <button class="btn btn-sm btn-outline-secondary" id="stayIn">نه، ادامه میدم</button>
    <button class="btn btn-sm btn-danger" id="exitNow">بله، خارج میشم</button>
  </div>
</div>

@once
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('openReviewModal');
        const backdrop = document.getElementById('reviewBackdrop');
        const modal = document.getElementById('reviewModal');
        const closeBtn = document.getElementById('closeReviewModal');
        const exitConfirm = document.getElementById('exitConfirm');
        const stayIn = document.getElementById('stayIn');
        const exitNow = document.getElementById('exitNow');

        openBtn?.addEventListener('click', () => {
          backdrop.classList.remove('d-none');
          modal.classList.remove('d-none');
          void backdrop.offsetWidth;
          void modal.offsetWidth;
          backdrop.classList.add('show');
          modal.classList.add('show');
        });

        closeBtn?.addEventListener('click', () => {
          exitConfirm.classList.remove('d-none');
        });

        stayIn?.addEventListener('click', () => {
          exitConfirm.classList.add('d-none');
        });

        exitNow?.addEventListener('click', () => {
          modal.classList.remove('show');
          backdrop.classList.remove('show');
          setTimeout(() => {
            modal.classList.add('d-none');
            backdrop.classList.add('d-none');
            exitConfirm.classList.add('d-none');
          }, 300);
        });
      });
    </script>
  @endpush
@endonce
