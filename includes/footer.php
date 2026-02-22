<footer>
  <div class="footer">
    <p>&copy 2026 GymFlow. All Rights Reserved.</p>
    <p>Email: gymflow@gmail.com | Phone: +94 123 456 789</p>
    <p>Follow us: 
      <a href="#">Facebook</a> | 
      <a href="#">Instagram</a> 
    </p>
  </div>
</footer>

<?php if (isset($extraScripts)): ?>
    <?php foreach ($extraScripts as $script): ?>
        <script src="js/<?php echo $script; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
