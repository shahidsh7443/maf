(function($) {
	$(function() {

		$('.thegem-import-output').each(function() {
			var $importOutput = $(this);
			var $progressBlock = $('<div class="progress-import-block" />');
			var $importStatus = $('<div class="import-status" />').appendTo($progressBlock);
			var $progressBar = $('<div class="progress-bar" />').appendTo($progressBlock);
			var $progressBarLine = $('<div class="progress-bar-line" />').appendTo($progressBar);
			var $importMessages = $('<div class="import-messages" />').appendTo($progressBlock);
			var $importButtons = $('.import-button', $importOutput);

			$('.import-variants', $importOutput).accordion({
				collapsible: true,
				header: 'h3',
				heightStyle: 'content'
			});
			$('.import-tabs', $importOutput).tabs({});

			window.onbeforeunload = function(e) {
				if($importOutput.data('proccess'))
				return 1;
			};

			var files_list = [];

			var import_start = function(import_part, import_pack, callback) {
				if(import_part == 'full') {
					import_start('media', import_pack, function() { import_start('posts', import_pack); });
				} else {
					$.ajax({
						url: thegem_import_data.ajax_url,
						data: { action: 'thegem_import_files_list', import_part: import_part, import_pack: import_pack},
						method: 'POST',
						timeout: 30000
					}).done(function(msg) {
						msg = jQuery.parseJSON(msg);
						$importStatus.html('<p>'+msg.status_text+'</p>');
						$importMessages.html('<p>'+msg.message+'</p>');
						if(msg.status) {
							files_list = msg.files_list;
							import_file(0, import_pack, callback);
						} else {
							$importStatus.add($importMessages).addClass('failed');
							$importOutput.data('proccess', false);
						}
					}).fail(function() {
						$importStatus.remove();
						$importMessages.html('<p>Ajax error. Try again...</p>');
						$importOutput.data('proccess', false);
					});
				}
			};

			var import_file = function(num, import_pack, callback) {
				if(files_list[num] != undefined){
					$progressBarLine.css({
						width: 100*num/files_list.length + '%'
					});
					$progressBarLine.text(parseFloat(100*num/files_list.length).toFixed(1) + '%');
					$.ajax({
						url: ajaxurl,
						data: {action: 'thegem_import_file', import_pack: import_pack, filename: files_list[num]},
						method: 'POST',
						timeout: 50000
					}).done(function(msg) {
						msg = jQuery.parseJSON(msg);
						import_file(num+1, import_pack, callback);
					}).fail(function() {
						import_file(num+1, import_pack, callback);
					});
				} else {
					$progressBarLine.css({
						width: '100%'
					});
					$progressBarLine.text('100%');
					if($.isFunction(callback)) {
						callback();
					} else {
                        $importStatus.remove();
                        $importOutput.data('proccess', false);
                        $importMessages.html('<p>All done. Have fun! ;)</p>');
                        delete_attachment_json_data();
					}
				}
			};

            var delete_attachment_json_data = function () {
                $.ajax({
                    url: ajaxurl,
                    data: {action: 'thegem_delete_attachment_json_data'},
                    method: 'POST',
                    timeout: 50000
                }).done(function(result) {}).fail(function(result) {});
            };

			$importButtons.click(function(e) {
				e.preventDefault();
				var $button = $(this);
				var import_part = $button.data('import-part');
				var import_pack = $button.data('import-pack');
				$importOutput.data('proccess', true)
				$('.thegem-import-prevent-message').remove();
				$button.closest('.import-variants').remove();
				$progressBlock.appendTo($importOutput);
				import_start(import_part, import_pack);
			});


			// single-import
            $('.single-import-form').on('submit', function(e){
                e.preventDefault();
                var data = $(this).serialize();
                if(data==='') {
                	alert('Please select the items to import!');
				} else {
                    $('.thegem-import-prevent-message').remove();
                    $('.import-variants').remove();
                    $importOutput.html('<p><div class="import-singles-loader"></div></p><p>Import is running. Please don\'t close this window until import is finished.</p>');
                    $(this).find('.import-singles-button').attr( 'disabled', true );
                    import_singles(data);
                }
			});

            var import_singles = function(data) {
                $.ajax({
                    url: ajaxurl,
                    data: { action: 'thegem_import_singles', ids: data},
                    method: 'POST'
                }).done(function(result) {
                    var msg = $.parseJSON(result);
                    if(msg.status === 1) {
                        import_singles_update_content(msg.data);
					} else {
                        $importOutput.html('<div class="import-status failed"><p>'+msg.status_text+'</p><p>'+msg.message+'</p></div>');
					}
                }).fail(function() {
                    $importOutput.html('<p>Ajax error. Try again...</p>');
                });
            };

            var import_singles_update_content = function(data) {
                $.ajax({
                    url: ajaxurl,
                    data: { action: 'thegem_import_singles_update_content', ids: data},
                    method: 'POST'
                }).done(function(result) {
                    result = jQuery.parseJSON(result);
                    if(result.status === 1) {
                        $importOutput.html('<p>All done. Have fun! ;)</p>');
					}
                }).fail(function() {
                    $importOutput.html('<p>Ajax error. Try again...</p>');
                });
            };


            var itemSingleCheckbox = $('.import-single-item-elem').find('input[type=checkbox]');
            var importSinglesPreviewBox = $('.import-singles-preview-box');

            itemSingleCheckbox.on('change', function() {
                var imgSrc = $(this).attr('data-src');
                var imgId = $(this).attr('data-id');
                var title = $(this).closest('.import-single-item-elem').find('.import-single-item-elem-title').text();

				if($(this).is(':checked')) {
					importSinglesPreviewBox.append('<div class="single-item-preview" id="'+imgId+'"><img src="' + imgSrc + '" alt="'+title+'"/></div>');
				} else {
					$('#'+imgId).remove();
				}
			});


            // remove-demo-content
            $('#btn-remove-demo-content').on('click', function() {
				$('.thegem-import-prevent-message').remove();
				$('.import-variants').remove();
				$importOutput.html('<p><div class="import-singles-loader"></div></p><p>Remove is running. Please don\'t close this window until remove is finished.</p>');

				$.ajax({
					url: ajaxurl,
					data: { action: 'thegem_remove_import_demo_content'},
					method: 'POST'
				}).done(function(result) {
					$importOutput.html('<p>All done. Have fun! ;)</p>');
				}).fail(function() {
					$importOutput.html('<p>Ajax error. Try again...</p>');
				});
            });

			// import widgets
            $('#btn-import-widgets').on('click', function() {
				$('.thegem-import-prevent-message').remove();
				$('.import-variants').remove();
				$importOutput.html('<p><div class="import-singles-loader"></div></p><p>Import is running. Please don\'t close this window until import is finished.</p>');

				$.ajax({
					url: ajaxurl,
					data: { action: 'thegem_import_widgets'},
					method: 'POST'
				}).done(function(result) {
					var msg = $.parseJSON(result);
					if(msg.status) {
                        $importOutput.html('<p>All done. Have fun! ;)</p>');
                    } else {
                        $importOutput.html('<div class="import-status failed"><p>'+msg.status_text+'</p><p>'+msg.message+'</p></div>');
                    }
				}).fail(function() {
					$importOutput.html('<p>Ajax error. Try again...</p>');
				});

            });

            // import mailchimp-forms
            $('#btn-import-mailchimp-forms').on('click', function() {
                $('.thegem-import-prevent-message').remove();
                $('.import-variants').remove();
                $importOutput.html('<p><div class="import-singles-loader"></div></p><p>Import is running. Please don\'t close this window until import is finished.</p>');

                $.ajax({
                    url: ajaxurl,
                    data: { action: 'thegem_import_mailchimp_forms'},
                    method: 'POST'
                }).done(function(result) {
                    var msg = $.parseJSON(result);
                    if(msg.status) {
                        $importOutput.html('<p>All done. Have fun! ;)</p>');
                    } else {
                        $importOutput.html('<div class="import-status failed"><p>'+msg.status_text+'</p><p>'+msg.message+'</p></div>');
                    }
                }).fail(function() {
                    $importOutput.html('<p>Ajax error. Try again...</p>');
                });

            });


		});


	});
})(jQuery);