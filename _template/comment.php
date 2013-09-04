<div class="comment">
	<div id="disqus_thread"></div>
	<script type="text/javascript">
		(function() {
		  var disqus_shortname = '<?= $data['config']['disqusId']; ?>';
      if (disqus_shortname === '') return;
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
	</script>
</div>
