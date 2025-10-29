<footer class="bg-secondary mt-auto" style="--bs-bg-opacity: .2;">
    <div class="container">
        <div class="row p-5 mt-3">
            <p class="text-center">&copy; Flow - Todos os direitos reservados.</p>
        </div>
    </div>
  </footer>
  
  
  <?php if(isset($page) && $page == 'index'): ?>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="assets/js/caleldario.js"></script>
  <?php endif; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>