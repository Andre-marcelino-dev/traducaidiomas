<script
  type="text/javascript"
  src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script type="text/javascript" src="{{ asset('traducaidiomas/js/slick.js') }}"></script>

<script src="{{ asset('traducaidiomas/js/lity.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- <script src="javascript.js"></script> -->

<script src="{{ asset('traducaidiomas/js/animacao.js') }}"></script>
<script src="{{ asset('js/chatbot.js') }}"></script>

<script
    type="text/javascript"
    src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script type="text/javascript" src="js/slick.js"></script>

  <script src="./js/lity.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="javascript.js"></script> -->

  <script src="./js/animacao.js"></script>
  <script src="./js/quiz.js"></script>

  {{-- resources/views/partials/script.blade.php --}}

<script>
    setTimeout(function () {
        const alerts = document.querySelectorAll('#flash-success, #flash-error');
        alerts.forEach(function (alert) {
            alert.style.transition = 'opacity 0.8s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 800);
        });
    }, 3000);
</script>
