	<footer id="footer"></footer>
</div>
    <script type="text/javascript">
        var psmpagesourcepath = <?php echo json_encode( substr($data['page']['sourcepath'] , 3 ) ) ?> ;
        var psmpagelemma = <?php echo json_encode( $data['page']['lemma'] )?> ;
    </script>
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="_make/write.jquery.js?v=q" type="text/javascript"></script>
</body>
</html>



