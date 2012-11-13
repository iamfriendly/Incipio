<?php
global $workingcode;
?>
<div id="csfooter">
<div style="" id="<?php echo $workingcode; ?>">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <script type="text/javascript">
        pp = {
            wc: "#<?php echo $workingcode ?>",
            dc: "<?php echo $this->getCode() ?>"
        }
    </script>
</form>

</div>
</div>