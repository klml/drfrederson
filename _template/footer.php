	<footer id="footer"></footer>
</div>
    <button id="edit" class="hidden">edit</button>
    
    <script type="text/javascript">
        var psmpagesource = <?php echo json_encode( substr($data['page']['filenamepath'] , 3 ) ) ?> ;
        var psmpagelemma = <?php echo json_encode( $data['page']['lemma'] )?> ;
    </script>
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="_make/write.jquery.js?v=q" type="text/javascript"></script>
</body>
</html>



