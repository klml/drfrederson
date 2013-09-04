<script type="text/javascript">
	(function () {
		var disqus_shortname = <?php if { ($data['config']['disqusId'] === '') echo '\"\"' } else { echo $data['config']['disqusId']; } ?>;
    if (disqus_shortname === '') return;
		var s = document.createElement('script'); s.async = true;
		s.type = 'text/javascript';
		s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
		(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
	}());
</script>
