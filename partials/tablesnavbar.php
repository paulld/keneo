<?php
  $current_url = end(explode("/", $_SERVER[REQUEST_URI]));
?>

<!-- LARGE SCREENS -->
<div class="container nav-tabs-outer tables-nav">
  <ul class="nav nav-tabs nav-justified">
    <?php
      $men= "SELECT * FROM rob_tables ORDER BY nom";
      $menu = $bdd->query($men);
      while ($donnee = $menu->fetch()) {
        $class_active = $donnee['lien'] == $current_url ? ' class="active"' : '';
        echo '<li'.$class_active.'>';
        echo '<a role="presentation" href="'.$donnee['lien'].'">'.$donnee['nom'].'</a>';
        echo '</li>';
      }
      $menu->closeCursor();
    ?>
  </ul>
</div>

<!-- SMALL SCREENS -->
<div class="container tables-links">
  <div class="col-xs-5 tables-links-text">
    Acc&eacute;der &agrave; la table :
  </div>
  <div class="col-xs-7">
    <select class="form-control" name="table" onchange="location=this.options[selectedIndex].value;" >
      <option></option>
      <?php
      $men= "SELECT * FROM rob_tables ORDER BY nom";
      $menu = $bdd->query($men);
      while ($donnee = $menu->fetch()) {
        $selected = $donnee['lien'] == $current_url ? ' selected="selected"' : '';
        echo '<option'.$selected.' value='.$donnee['lien'].'>'.$donnee['nom'].'</option>';
      }
      $menu->closeCursor();
      ?>
    </select>
  </div>
</div>