<?php
if ($this->debug) {
?>
View Debug:<br><?php $this->dbg($this); ?><br>
POST Debug:<br><?php $this->dbg($_POST); ?>
<?php
}
?>
</body>
</html>