</main>
<footer class="p-4 mt-8 text-center text-sm text-gray-600">
  &copy; <?= date('Y') ?> blue
</footer>
<script>
function openQuickView(id){
  fetch('/blue/product_modal.php?id='+id)
    .then(r=>r.text()).then(html=>{
      let div=document.createElement('div');
      div.innerHTML=html;
      document.body.appendChild(div);
    });
}
</script>
</body>
</html>
