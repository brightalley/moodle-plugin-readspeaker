YUI.add("moodle-block_readspeaker_embhl-ReadSpeaker", function(){

	M.block_RS = M.block_RS || {};
	M.block_RS.ReadSpeaker = {
		init: function() {
			if (!window.rsConf) window.rsConf = {};

			// Check params
			var dr = (window.rsDocReaderConf && window.rsDocReaderConf.cid) ? '&dload=DocReader.Moodle.AutoAdd' : '';
			var customerid = (window.rsConf && window.rsConf.moodle && window.rsConf.moodle.customerid) ? window.rsConf.moodle.customerid : 'default';
			var region = (window.rsConf && window.rsConf.moodle && window.rsConf.moodle.region) ? window.rsConf.moodle.region : 'eu';

			var showincontent = (window.rsConf && window.rsConf.moodle && window.rsConf.moodle.showincontent) ? window.rsConf.moodle.showincontent : '';
			var editingMode = (window.rsConf && window.rsConf.moodle && window.rsConf.moodle.em) ? window.rsConf.moodle.em : 0;


			var scriptSrc = 'https://cdn-%region%.readspeaker.com/script/%customerid%/webReaderForEducation/moodle/current/webReader.js',
				scriptParams = '?pids=embhl' + dr;
			scriptSrc = scriptSrc.replace("%customerid%", customerid);
			scriptSrc = scriptSrc.replace("%region%", region);

			window.rsConf.params = scriptSrc + scriptParams;

			var head = document.getElementsByTagName('HEAD').item(0);
			var scriptTag = document.createElement("script");
			scriptTag.setAttribute("type", "text/javascript");
			scriptTag.src = scriptSrc;

			var callback = function() {
				ReadSpeaker.init();
				if (showincontent !== "") {
					var rsButton = document.getElementById('readspeaker_button1'),
					readArea = document.getElementById(showincontent);
					// Remove the compact class
					rsButton.classList.remove('rscompact');
					// Move the Listen button
					if (rsButton && readArea) {
						readArea.prepend(rsButton.parentElement.removeChild(rsButton));
					}
					// Remove the empty block if we are NOT in editing mode. Then keep the block, so user can change settings.
					if (!editingMode) {
						var rs_blocks = document.getElementsByClassName('block_readspeaker_embhl block');
						for (var i = 0; i < rs_blocks.length; i++) {
							rs_blocks[0].style.display = "none";
						}
					}
				}
			};

			scriptTag.onreadystatechange = scriptTag.onload = function() {
		        var state = scriptTag.readyState;
		        if (!callback.done && (!state || /loaded|complete/.test(state))) {
		            callback.done = true;
		            callback();
		        }
		    };

		    // use body if available. more safe in IE
		    (document.body || head).appendChild(scriptTag);
		}
	};
}, "@VERSION@", {
	requires: ["node"]
});
