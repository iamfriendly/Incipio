function molecule_image_widget_change(image)
{
	if (chemistry.upload_caller != null)
	{
		jQuery(chemistry.upload_caller).closest('fieldset').find('img.upload_image').attr('src', image);
		jQuery(chemistry.upload_caller).closest('fieldset').find('input.upload_image').val(image);
	}
}

function molecule_gallery_widget_change(image)
{
	if (chemistry.upload_caller != null)
	{
		jQuery(chemistry.upload_caller).closest('.group-item').find('img.upload_image').attr('src', image);
		jQuery(chemistry.upload_caller).closest('.group-item').find('input.upload_image').val(image);
	}
}

function molecule_gallery_widget_insert(image)
{
	if (chemistry.upload_caller != null)
	{
		var $container = jQuery(chemistry.upload_caller).closest('fieldset');
		var $gallery = $container.find('.group-content').children().eq(0);
		var $first = $container.find('.group-prototype').children().eq(0);
		var $clone = $first.clone();

		$clone.find('input, textarea, select').val('');
		$clone.find('textarea').text('');
		$clone.find('img').attr('src', image);
		$clone.find('input.upload_image').val(image);

		$gallery.append($clone.clone().show().css('display',''))

		if ( ! $gallery.hasClass('ui-sortable') || ! $gallery.hasClass('ui-sortable-refreshed'))
		{
			$gallery.sortable({
				handle: '.group-item-title',
				appendTo: 'parent',
				tolerance: 'pointer',
				delay: 100,
				forceHelperSize: true,
				start: function (evt, ui)
				{
					ui.helper.css({width:ui.item.width() + 60});
					ui.placeholder.css({height: ui.item.children().eq(0).height() + 32});
				}
			});
			$gallery.addClass('ui-sortable-refreshed');
		} else
		{
			$gallery.sortable().sortable('refresh');
		}

		chemistry.set_dynamic_label($gallery);

	}
}

(function($){$(function()
{
	$.expr[':'].icontains = function(obj, index, meta, stack){return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;};

	function check_current_indicator(elem)
	{
		var $parent = elem.closest('fieldset');
		var $current = $parent.find('select[name*=current]');

		var $items = $parent.find('.group-item');

		var current_item = $current.children('option:selected').index();
		var items = $current.children('option').length;

		if (items < $items.length)
		{
			for (var i = 0; i < $items.length - items; i++)
			{
				$current.append('<option value="' + (items + i) + '">' + (items + i) + '</option>');
			}
		} else
		{
			var $options = $current.children('option').slice(0, $items.length - 1);
			$current.children('option').remove();
			$current.append($options);
		}
	}

	function update_excerpt($widget)
	{
		var $parent = ($widget.hasClass('molecule-widget') ? $widget : $widget.closest('.molecule-widget'));

		var excerpt = '';

		if ( ! $parent.parent().hasClass('molecule-widget-core'))
		{
			$parent.find('input[type=text], textarea').each( function()
			{
				var val = $(this).val();

				if (val.length > 0)
				{
					excerpt += ' ' + val;
				}
			});
		}

		if (excerpt.length > 0)
		{
			excerpt = excerpt.replace(/<\/?[^>]+>/gi, '');
		}

		if (excerpt.length > 170)
		{
			excerpt = excerpt.substring(0, 170) + '...';
		}

		excerpt = $.trim(excerpt);

		$parent.find('.molecule-widget-excerpt').text(excerpt);

		if (excerpt.length > 0)
		{
			$parent.find('.molecule-widget-excerpt').show();
		} else
		{
			$parent.find('.molecule-widget-excerpt').hide();
		}
	}

	function filter_widgets()
	{
		var filter = $(this).val().toLowerCase();

		var $parent = $(this).closest('#molecule-widgets');

		if (filter != '')
		{
			$parent.find('.molecule-widget-wrapper').filter( function(index)
			{
				var data = $(this).find('.molecule-widget-title').text().toLowerCase() + ' ' + $(this).find('.molecule-widget-label').text().toLowerCase();

				return data.indexOf(filter) < 0;
			}).hide();

			$parent.find('.molecule-widget-wrapper').filter( function(index)
			{
				var data = $(this).find('.molecule-widget-title').text().toLowerCase() + ' ' + $(this).find('.molecule-widget-label').text().toLowerCase();

				return data.indexOf(filter) >= 0;
			}).show();
		} else
		{
			$parent.find('.molecule-widget-wrapper').show();
		}
	}

	function init_richtext($textarea, force)
	{
		if (typeof force == 'undefined')
		{
			force = false;
		}

		var id = $textarea.attr('id');

		var options = {mode:"exact",width:"100%",theme:"advanced",skin:"wp_theme",language:"en",spellchecker_languages:"+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv",theme_advanced_toolbar_location:"top",theme_advanced_toolbar_align:"left",theme_advanced_statusbar_location:"bottom",theme_advanced_resizing:true,theme_advanced_resize_horizontal:false,dialog_type:"modal",formats:{alignleft : [{selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'left'}},{selector : 'img,table', classes : 'alignleft'}],aligncenter : [{selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'center'}},{selector : 'img,table', classes : 'aligncenter'}],alignright : [{selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'right'}},{selector : 'img,table', classes : 'alignright'}],strikethrough : {inline : 'del'}},relative_urls:false,remove_script_host:false,convert_urls:false,remove_linebreaks:true,gecko_spellcheck:true,keep_styles:false,entities:"38,amp,60,lt,62,gt",accessibility_focus:true,tabfocus_elements:"major-publishing-actions",media_strict:false,paste_remove_styles:true,paste_remove_spans:true,paste_strip_class_attributes:"all",paste_text_use_dialog:true,extended_valid_elements:"article[*],aside[*],audio[*],canvas[*],command[*],datalist[*],details[*],embed[*],figcaption[*],figure[*],footer[*],header[*],hgroup[*],keygen[*],mark[*],meter[*],nav[*],output[*],progress[*],section[*],source[*],summary,time[*],video[*],wbr",wpeditimage_disable_captions:true,wp_fullscreen_content_css:"",plugins:"inlinepopups,spellchecker,tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,-chemistry_wysiwig",elements:"content",wpautop:true,apply_source_formatting:true,theme_advanced_buttons1:"bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,wp_more,|,spellchecker,wp_fullscreen,wp_adv",theme_advanced_buttons2:"formatselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo,wp_help",theme_advanced_buttons3:"highlight,dropcap,tooltip,button,separator,message,blockquote-,pullquote,list,separator,media-grid,video,separator,table,accordion,tabs",theme_advanced_buttons4:""};

		//tinyMCE.init(options);

		/*if (typeof tinyMCEPreInit.mceInit.content == 'undefined')
		{
			delete tinyMCEPreInit.mceInit["theEditor"];

		}

		tinyMCE.init(typeof tinyMCEPreInit.mceInit.content != 'undefined' > 0 ? tinyMCEPreInit.mceInit.content : tinyMCEPreInit.mceInit);*/

		if (typeof tinyMCEPreInit != 'undefined' && typeof tinyMCEPreInit.mceInit.content == 'undefined')
		{
			//tinyMCE.init(tinyMCEPreInit.mceInit.content);
		}

		if (typeof tinyMCE != 'undefined' || force)
		{
			tinyMCE.execCommand('mceAddControl', false, id);
		}

	}

	function destroy_richtext($textarea, force)
	{
		if (typeof force == 'undefined')
		{
			force = false;
		}

		var id = $textarea.attr('id');

		if (typeof tinyMCE != 'undefined' && typeof tinyMCE.getInstanceById(id) != 'undefined')
		{
			tinyMCE.execCommand('mceFocus', false, id);

			tinyMCE.triggerSave();
			//$textarea.val(tinyMCE.getInstanceById(id).getContent());
			__RICH_CONTENT__ = tinyMCE.getInstanceById(id).getContent();

			if ($('.widgets-holder-wrap').length > 0 || force)
			{
    			tinyMCE.execCommand('mceRemoveControl', false, id);
    			//tinyMCE.execCommand('mceRemoveEditor', false, id);
    		}
    	}
	}

	function update_widget_data(element)
	{
		var $data = element.find('input[name^=chemistry_molecule_widget], select[name^=chemistry_molecule_widget], textarea[name^=chemistry_molecule_widget], button[name^=chemistry_molecule_widget]');

		var NEW_ID = new Date().getTime();

		$data.each( function()
		{
			var attrs = { 'name': 'name', 'id': 'id' };

			for (var attr in attrs)
			{
				if (typeof $(this).attr(attr) != 'undefined' && $(this).attr(attr) !== false)
				{
					var data = 'chemistry_molecule_widget[___POSITION___][___CHEMISTRYROW___][___CHEMISTRYCOL___][___CHEMISTRYID___][___MODULE___]';
					var current_data = /\[(.*?)\]\[(.*?)\]\[(.*?)\]\[(.*?)\]\[(.*?)\]/.exec($(this).attr(attr));

					if (current_data != null)
					{
						var is_array = false;

						if ($(this).attr(attr).length > 2)
						{
							is_array = ($(this).attr(attr).substring($(this).attr(attr).length - 2) == '[]');
						}

						if (current_data.length == 6)
						{
							data = data.replace(/___MODULE___/g, current_data[5]);
						}

						if (current_data.length == 6 && current_data[4] != '___CHEMISTRYID___')
						{
							data = data.replace(/___CHEMISTRYID___/g, current_data[4]);
						} else
						{
							data = data.replace(/___CHEMISTRYID___/g, NEW_ID);
						}

						var $row = $(this).parents('div[class*=molecule-widget-type-row]');

						if ($row.length > 0)
						{
							data = data.replace(/___CHEMISTRYROW___/g, $row.index());

							var $column = $(this).parents('div.molecule-widget-column');

							if ($column.length > 0)
							{
								data = data.replace(/___CHEMISTRYCOL___/g, $column.index());
							}
						} else
						{
							data = data.replace(/___CHEMISTRYROW___/g, $(this).parents('.molecule-widget-wrapper').index());
						}

						var $location = $(this).parents('.molecule-location-wrapper:not(.read-only) .molecule-location');

						if ($location.length > 0)
						{
							data = data.replace(/___POSITION___/g, $location.attr('id').replace('molecule-location-', ''));
						}

						$(this).attr(attr, data + (is_array ? '[]' : ''));
					}
				}
			}
		});

		$(this).addClass('initialized');
	}

	function get_widget_data($elem, deep)
	{
		var $settings;
		var $location;
		var $data;

		if (deep === true)
		{
			$settings = $elem.find('input[name^=chemistry_molecule_widget], select[name^=chemistry_molecule_widget], textarea[name^=chemistry_molecule_widget], button[name^=chemistry_molecule_widget]');
			$location = [];
		} else
		{
			$settings = $elem.find('.molecule-widget-content-form').eq(0).find('input[name^=chemistry_molecule_widget], select[name^=chemistry_molecule_widget], textarea[name^=chemistry_molecule_widget], button[name^=chemistry_molecule_widget]');
			$location = $elem.find('.molecule-widget').eq(0).find('input[name^=chemistry_molecule_widget]');
		}
		var $data = $.merge($settings, $location);

		return $data;
	}

	function is_top_level($elem)
	{
		if ($elem.hasClass('molecule-widget-core') || $elem.parent('.molecule-location').length > 0)
		{
			return true;
		} else
		{
			return false;
		}
	}

	function update_widget_col_order($elem)
	{
		var id = $elem.parent('.molecule-widget-column').length > 0 ? $elem.parent('.molecule-widget-column').index() : '___CHEMISTRYCOL___';

		$elem.each(function ()
		{
			var $data = get_widget_data($(this));

			$data.each(function ()
			{
				$name = $(this).attr('name');
				$(this).attr('name', $name.replace(/((\[.*?\]){2})(\[(.*?)\])/, '$1[' + id + ']'));
			});
		});
	}

	function update_widget_row_order($elem)
	{
		var row_id;

		//work only on this elem if it's not top level
		if ( ! is_top_level($elem.eq(0)))
		{
			$elem = $elem.eq(0);
			row_id = $elem.parents('.molecule-widget-core').index();
		} else
		{
			row_id = $elem.eq(0).index();
		}

		$elem.each(function (elem_id)
		{
			var top_level_elem = is_top_level($(this));
			var $data = get_widget_data($(this), true);

			if (top_level_elem && elem_id !== 0)
			{
				row_id += 1;
			}

			$data.each(function ()
			{
				$name = $(this).attr('name');
				$(this).attr('name', $name.replace(/((\[.*?\]){1})(\[(.*?)\])/, '$1[' + row_id + ']'));
			});
			//row_id += 1
		})
	}

	function get_unique_id(id_list)
	{
		var id = new Date().getTime()
		if ( ! id_list[id])
		{
			id_list[id] = true;
			return id;
		} else
		{
			//console.log('duplicate id occurrence');
			return get_unique_id(id_list);
		}
	}

	function make_duplicate($elem, update_widget_data, append_widget_to_dom)
	{
		var $clone;
		var ID_LIST = {};

		//clone does not get through sortable destroy properly so destroy it on $elem element before doing anything else
		$elem.find('.ui-sortable').sortable().sortable('destroy');
		$elem.find('.group-content').children().sortable().sortable('destroy');

		//check if a widget contains any other widgets and if so match all widgets
		if ($elem.find('.molecule-widget-wrapper').length > 0)
		{
			$elem = $.merge($elem, $elem.find('.molecule-widget-wrapper'))
		}

		//tinyMCE editor must be entirely destroyed before making a clone, so if there is one, uninit it from $elem first
		$elem.each(function ()
		{
			var $textarea;
			var textarea_id;

			//this affects rich text widget only and not rich texts within group items within services, testimonials etc.
			if ($(this).hasClass('molecule-widget-type-rich-text'))
			{
				$textarea = $(this).find('textarea');
				textarea_id = $textarea.attr('id');

				if ($textarea.length == 1 && ($textarea.hasClass('tinymce') || $('.widgets-holder-wrap').length > 0))
				{
					destroy_richtext($textarea);
					tinyMCE.execCommand('mceRemoveEditor', false, textarea_id);
				}
			}
		});

		//now we can clone safely
		$clone = $elem.eq(0).clone(true, true);
		$clone = $.merge($clone, $clone.find('.molecule-widget-wrapper'));

		if (update_widget_data === true)
		{
			$clone.each(function ()
			{
				var $data = get_widget_data($(this));
				var NEW_ID = get_unique_id(ID_LIST);
				var $textarea;
				var textarea_id;

				$data.each(function ()
				{
					$name = $(this).attr('name');
					$(this).attr('name', $name.replace(/((\[.*?\]){3})(\[(.*?)\])/, '$1[' + NEW_ID + ']'));
				});

				if ($(this).hasClass('molecule-widget-type-rich-text'))
				{
					$textarea = $(this).find('textarea');
					textarea_id = $textarea.attr('id');

					if ($textarea.length == 1 && ($textarea.hasClass('tinymce') || $('.widgets-holder-wrap').length > 0))
					{
						$textarea.attr('id', $textarea.attr('name'));
					}
				}
			});
		}

		$.merge($elem.eq(0), $clone.eq(0)).find('.group-content').children().sortable({
			handle: '.group-item-title',
			appendTo: 'parent',
			tolerance: 'pointer',
			delay: 100,
			forceHelperSize: true,
			start: function (evt, ui)
			{
				ui.helper.css({width:ui.item.width() + 60});
				ui.placeholder.css({height: ui.item.children().eq(0).height() + 32});
			},
		});

		if (append_widget_to_dom === true)
		{
			$clone.eq(0).insertAfter($elem.eq(0)).hide().slideDown(500).queue(function ()
			{
				$(this)
					.css('display', 'block')
					.dequeue();
			});

			//$clone.eq(0).find('.molecule-widget-row-options').hide();

			update_widget_row_order($elem.eq(0).nextAll());

			$('.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each(make_sortable);
		} else
		{
			return $clone.eq(0);
		}
		/*
		$('.molecule-location .molecule-widget').each(function ()
		{
			var name = $(this).find('*[name*="chemistry_molecule_widget"]').eq(0).attr('name');
			var re = new RegExp('\\[\\S*\\]*');
			name = name.match(re).join('');

			var $bar = $(this).find('.molecule-widget-bar').eq(0);
			var $debug_name_attr = $bar.find('.debug-name-attr');

			$debug_name_attr.length > 0 ? $debug_name_attr.text(name) : $bar.prepend('<div class="debug-name-attr">' + name + '</div>');
		});
		*/
	}

	/*function make_sortable()
	{
		$(this).sortable
		({
			handle: '.molecule-widget-bar',
			connectWith: '.molecule-widget-row > div.molecule-widget-column, .molecule-location'
		});
	}

	$('.molecule-location, .molecule-widget-row > div.molecule-widget-column').each(make_sortable);

	$('#molecule-widgets .molecule-widget-wrapper').draggable
	({
		connectToSortable: '.molecule-location.ui-sortable, .molecule-widget-row > div.molecule-widget-column.ui-sortable',
		helper: 'clone',
		stop: function(e, ui)
		{
			$('.molecule-location, .molecule-widget-row > div.molecule-widget-column').each(make_sortable);
		}
	});*/

	// code above should work, but it doesnt, sometimes creates duplicated placeholders and appends elements twice
	// so..... HACKORING BEGIN
	// needs optimising?


	function make_sortable()
	{
		$(this).sortable().sortable('destroy');

		$(this).sortable
		({
			handle: '.molecule-widget > .molecule-widget-bar',
			connectWith: '.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column',
			appendTo: 'body',
			distance: 40,
			forceHelperSize: true,
			tolerance: 'pointer',
			helper: function(e, ui)
			{
				if (ui.hasClass('molecule-widget-core'))
				{
					$('.molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each( function()
					{
						$(this).sortable().sortable('disable');
					});
				}

				$('.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').sortable().sortable('refresh');

				return ui;
				//return make_clonable(e, ui);
			},
			start: function (e, ui)
			{
				chemistry.widget_drag = true;
				ui.item.attr('data-top-level', is_top_level(ui.item));
				ui.item.attr('data-prev-id', ui.item.index());
			},
			beforeStop: function(e, ui)
			{
				if ($(ui.helper).hasClass('molecule-widget-core'))
				{
					$('.molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each( function()
					{
						$(this).sortable().sortable('enable');
						$(this).sortable().sortable('refresh');
					});
				}
			},
			stop: function(e, ui)
			{
				var prev_id = parseInt(ui.item.attr('data-prev-id'), 10);
				var was_top = ui.item.attr('data-top-level') === 'true' ? true : false;
				var is_top = is_top_level(ui.item);
				var reorder_start_id = null;
				var reorder_self = null;
				var reorder_col = null;

				if (was_top === true && is_top === true)
				{
					reorder_start_id = Math.min(prev_id, ui.item.index());
				} else if (was_top === true && is_top === false)
				{
					reorder_start_id = prev_id;
					reorder_self = true;
					reorder_col = true;
				} else if (was_top === false && is_top === true)
				{
					reorder_start_id = ui.item.index();
					reorder_self = true;
					reorder_col = true;
				} else if (was_top === false && is_top === false)
				{
					reorder_self = true;
					reorder_col = true;
				}

				if (reorder_start_id !== null)
				{
					update_widget_row_order($('.molecule-location > .molecule-widget-wrapper').slice(reorder_start_id));
				}

				if (reorder_self === true)
				{
					update_widget_row_order(ui.item);
				}

				if (reorder_col === true)
				{
					update_widget_col_order(ui.item);
				}

				ui.item.attr('data-prev-id', '');
				ui.item.attr('data-top-level', '')

				$('.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each( function()
				{
					$(this).sortable().sortable('enable');
					$(this).sortable().sortable('refresh');
				});

				chemistry.widget_drag = false;
			},
			out: function(e, ui)
			{
				/*if ($(ui.placeholder).parent().hasClass('molecule-widget-column'))
				{
					$('.molecule-location-wrapper:not(.read-only) .molecule-location').each( function()
					{
						$(this).sortable('enable');
						$(this).sortable('refresh');
					});
				}*/
			},
			over: function(e, ui)
			{
				/*if ($(ui.placeholder).parent().hasClass('molecule-widget-column'))
				{
					$('.molecule-location-wrapper:not(.read-only) .molecule-location').each( function()
					{
						$(this).sortable('disable');
					});
				} else
				{
					$(this).sortable('enable');
				}*/

				$('.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each( function()
				{
					$(this).sortable().sortable('refresh');
				});
			},
			update: function (e, ui)
			{
				//if (ui.item.css('display', 'none'))
				//{
				//	ui.item.css('display', 'block');
				//}
			}
		});
	}

	function make_clonable(e, ui)
	{
		var copy = ui.clone().insertAfter(ui);

		$('.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each(make_sortable);

		if (copy.hasClass('molecule-widget-core'))
		{
			$('.molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each( function()
			{
				$(this).sortable().sortable('disable');
			});
		}

		$('#molecule-widgets').sortable().sortable('refresh');

		return ui.clone();
	}

	var on_molecule_widget_modal_open = function ($widget)
	{
		var $textarea;

		//this was used only for widget modals before. now widget select modal uses this function too
		chemistry.$current_molecule_widget_modal = $widget;

		if ($widget.attr('id') !== 'molecule-widgets')
		{
			chemistry.set_tabs($widget);
			chemistry.set_dynamic_label($widget);
			$widget.find('.group-content-wrap').each(function ()
			{
				if ($(this).find('.group-content').children('.cols').children().length > 0)
				{
					$(this).find('.buttonset-1').eq(1).show();
				} else
				{
					$(this).find('.buttonset-1').eq(1).hide();
				}
			});

			chemistry.$current_molecule_widget_modal_clone = make_duplicate($widget);

			if ($widget.hasClass('molecule-widget-type-rich-text'))
			{
				$textarea = $widget.find('textarea');

				if ($textarea.length == 1 && ($textarea.hasClass('tinymce') || $('.widgets-holder-wrap').length > 0))
				{
					init_richtext($textarea);
				}
			}
		}
	}

	var on_molecule_widget_modal_close = function ()
	{
		delete chemistry.$current_molecule_widget_modal;
	}

	chemistry.cancel_molecule_modal = function (evt)
	{
		var $modal = chemistry.$current_molecule_widget_modal;
		var $modal_clone = chemistry.$current_molecule_widget_modal_clone;

		if ( ! chemistry.$current_molecule_widget_modal)
		{
			return false;
		}

		if ($modal.attr('id') === 'molecule-widgets')
		{
			$modal.hide();
			$('#molecule-widget-overlay').stop(true, true).fadeTo('fast', 0.0, function() { $(this).hide(); });
			on_molecule_widget_modal_close();
		} else
		{
			if ($modal.find('.molecule-widget-inner:visible .molecule-widget-rich-form').length > 0)
			{
				if (confirm(chemistry.molecule_lang.changes))
				{
					var $textarea = $modal.find('.molecule-widget-inner:visible #molecule-rich-content');
					destroy_richtext($textarea, true);

					$modal.find('.molecule-widget-inner:visible .molecule-widget-rich-form').remove();
					$modal.find('.molecule-widget-inner:visible .molecule-widget-content-form').show();
				}
			} else
			{
				if (confirm(chemistry.molecule_lang.quit))
				{
					if ( ! $modal.hasClass('chemistry-initial'))
					{
						$modal_clone.insertAfter($modal);
						$modal.find('.save').click();
						chemistry.remove_widget($modal, true);
						//$modal.find('.save').click();
					} else
					{
						$modal.find('.save').click()
						chemistry.remove_widget($modal, true);
						//chemistry.remove_widget($modal_clone, true);
					}

					on_molecule_widget_modal_close();
				}
			}
		}

		if (evt)
		{
			evt.preventDefault();
		}

		return false;
	}

	chemistry.remove_widget = function ($widget, force)
	{
		var $next;

		if (force === true)
		{
			$next = $widget.eq(0).nextAll();
			$widget.remove();
			update_widget_row_order($next);
			$('#molecule-widget-overlay').stop(true, true).fadeTo('fast', 0.0, function() { $(this).hide(); });
		} else
		{
			if (confirm(chemistry.molecule_lang.sure))
			{
				$next = $widget.eq(0).nextAll();
				$widget.remove();
				update_widget_row_order($next);
				$('#molecule-widget-overlay').stop(true, true).fadeTo('fast', 0.0, function() { $(this).hide(); });
			}
		}

		return false;
	}

	chemistry.recursively_show = function ($elem)
	{
		$elem.addClass('dynamic-label-guard');

		if ($elem.parent().is(':hidden'))
		{
			chemistry.recursively_show($elem.parent());
		}
	}

	chemistry.remove_dynamic_label_guard = function ($elem)
	{
		$elem.removeClass('dynamic-label-guard').find('.dynamic-label-guard').removeClass('dynamic-label-guard');
	}

	chemistry.set_dynamic_label = function ($widget)
	{
		var $label;

		if ( ! $widget )
		{
			if (chemistry && chemistry.$current_molecule_widget_modal)
			{
				$widget = chemistry.$current_molecule_widget_modal;
			}
		}

		//careful with the scope though
		//$label = $widget.find('.chemistry-form label');
		$label = $widget.find('label');

		if ($label.hasClass('label-dynamic'))
		{
			$label.each(function ()
			{
				var $field = $(this).children('input:not(input[type="checkbox"]):not(input[type="radio"]), select');
				$field.each(function ()
				{
					$(this).css('width', '');
				});
			});
		}

		$label.each(function ()
		{
			var $field = $(this).children('input:not(input[type="checkbox"]):not(input[type="radio"]), select');
			var $tooltip = $(this).children('small');
			var $label_title = $(this).children('.label-title');
			var $tooltip_handle;
			var label_w = $(this).width();

			$(this).addClass('label-dynamic');

			if ($(this).children('input[type="checkbox"], input[type="radio"]').length > 0)
			{
				$label_title
					.prepend($(this).children('input[type="checkbox"], input[type="radio"]'))
					.addClass('expanded-title');
			}

			if (label_w === 0)
			{
				chemistry.recursively_show($(this));
				label_w = $(this).width();
			}

			if ($tooltip.length !== 0)
			{
				$tooltip
					.css({'top': 0, 'bottom': 'auto'})
					.addClass('label-tooltip')
					.css({
						'left': $label_title.outerWidth() - $tooltip.width() / 2
					});

				if ($label_title.children('.label-tooltip-handle').length === 0)
				{
					$tooltip_handle = $('<div class="label-tooltip-handle"></div>');

					$label_title.prepend($tooltip, $tooltip_handle);

				}
			}

			if($field.length !== 0)
			{
				$field.width($(this).width() - $label_title.outerWidth());
			}

			chemistry.remove_dynamic_label_guard($widget);
		});

		if ( ! chemistry.dynamic_label_tooltip_live_events)
		{
			chemistry.dynamic_label_tooltip_live_events = true;

			$('.chemistry-form .label-tooltip-handle').live('mouseenter', function ()
			{
				var $parent = $(this).parents('.molecule-widget-inner').eq(0);
				var $tooltip = $(this).siblings('.label-tooltip');

				$tooltip.css({'display': 'block', opacity: 0, 'top': 0});

				if ($parent.length > 0)
				{
					var min_x = $parent.offset().left
					var max_x = $parent.offset().left + $parent.outerWidth();
					var shift_x = 0;
					var min_y = $parent.offset().top;
					var max_y = $parent.offset().top + $parent.height();
					var shift_y = $tooltip.outerHeight();

					if ($tooltip.offset().left < min_x)
					{
						shift_x = min_x - $tooltip.offset().left + 8;
					} else if ($tooltip.offset().left + $tooltip.outerWidth() > max_x)
					{
						shift_x = max_x - ($tooltip.offset().left + $tooltip.outerWidth() + 30);
					}

					if ($tooltip.offset().top - $tooltip.outerHeight() < min_y)
					{
						shift_y = $tooltip.offset().top - min_y - 10;
					}
				}

				if (shift_x !== 0)
				{
					$tooltip.css({'left': $tooltip.position().left + shift_x});
				}

				$tooltip
					.stop(true, true)
					.delay(250)
					.animate({
						'top': -shift_y,
						'opacity': 1
					}, 250);
			}).live('mouseleave', function ()
			{
				var $tooltip = $(this).siblings('.label-tooltip');

				$tooltip
					.stop(true, true)
					.delay(250)
					.animate({
						'top': -$tooltip.outerHeight() * 2,
						'opacity': 0
					}, 250, function ()
					{
						$(this).css({'display': 'none'});
					});
			});
		}
	}

	chemistry.set_tabs = function ($widget)
	{
		if ($widget.attr('data-chemistry-tabs') && $widget.attr('data-chemistry-tabs') === 'set')
		{
			return false;
		}

		if ( ! chemistry.tab_title_live_events)
		{
			$('.chemistry-tabs .chemistry-tab-title').live('click', function ()
			{
				var window_pos_y = $(window).scrollTop();
				var $tab_content;
				var id = $(this).index();

				if ($(this).parents('.molecule-widget-wrapper').length === 0)
				{
					$tab_content = $(this).parents('.chemistry-tabs').eq(0).find('.chemistry-tab-content');
				} else
				{
					$tab_content = $(this).parents('.molecule-widget-wrapper').find('.chemistry-tabs').eq(1).find('.chemistry-tab-content');
				}

				if ( ! $(this).hasClass('chemistry-current'))
				{
					$(this).siblings().removeClass('chemistry-current')
					$(this).addClass('chemistry-current')
					$tab_content
						.removeClass('chemistry-current')
						.stop(true).fadeOut(250)
						.eq(id).addClass('chemistry-current').stop(true).fadeIn(250);
				}

				$(window).scrollTop(window_pos_y);

				//evt.preventDefault();
			});

			chemistry.tab_title_live_events = true;
		}

		var $tab_title = $widget.find('.chemistry-tab-title');
		var $tab_content = $widget.find('.chemistry-tab-content');
		var has_visible_tab;
		var $widget_title_bar = $widget.find('.molecule-widget-content .molecule-widget-bar').eq(0); //this is chemistry molecule modal custom var (see notes below);

		$tab_content.each(function (id)
		{
			$(this)
				.hide()
				.attr('data-tab-content-id', id);
		});

		$tab_title.each(function (id)
		{
			if ($(this).hasClass('chemistry-current'))
			{
				$tab_content.eq(id).show();
				has_visible_tab = true;
			}

			$(this)
				.attr('data-tab-id', id);
		});

		if ( ! has_visible_tab)
		{
			$tab_title.eq(0).addClass('chemistry-current')
			$tab_content.eq(0).addClass('chemistry-current').show();
		}

		$tab_title.wrapAll('<div class="chemistry-tab-title-wrap"></div>');
		$tab_content.wrapAll('<div class="chemistry-tab-content-wrap"></div>');

		//the below is modified behaviour for usage within chemistry molecule widget modal window
		if ($widget_title_bar.length === 0)
		{
			$.merge($tab_title.parent(), $tab_content.parent()).wrapAll('<div class="chemistry-tabs chemistry-tabs-x chemistry-tabs-left"></div>');
			//the above is default behaviour
		} else
		{
			$tab_title.parent().wrap('<div class="chemistry-tabs chemistry-tabs-x chemistry-tabs-left"></div>');
			$tab_title.parent().parent().appendTo($widget_title_bar);
			$tab_content.parent().wrap('<div class="chemistry-tabs chemistry-tabs-x chemistry-tabs-left"></div>');
		}

		$widget.attr('data-chemistry-tabs', 'set');
	}

	// drag drop for widget list
	// replaced by modal box
	/*$('#molecule-widgets').sortable
	({
		connectWith: '.molecule-location, .molecule-widget-row > div.molecule-widget-column',
		helper: make_clonable,
		beforeStop: function(e, ui)
		{
			if ($(ui.helper).hasClass('molecule-widget-core'))
			{
				$('.molecule-widget-row > div.molecule-widget-column').each( function()
				{
					$(this).sortable('enable');
				});
			}

			if ($(ui.item).parents('.molecule-location').length == 0)
			{
				$(ui.item).remove();
			}
		},
		stop: function(e, ui)
		{
			update_widget_data($('.molecule-location-wrapper .molecule-widget-wrapper'));
		}
	}).disableSelection();*/

	$('input[name=molecule-widget-filter]').live('keyup', filter_widgets).live('change', filter_widgets);

	$('.group-content').children().sortable({
		handle: '.group-item-title',
		appendTo: 'parent',
		tolerance: 'pointer',
		delay: 100,
		forceHelperSize: true,
		start: function (evt, ui)
		{
			ui.helper.css({width:ui.item.width() + 60});
			ui.placeholder.css({height: ui.item.children().eq(0).height() + 32});
		},
	});

	$('button.molecule-widget-group-item-add').live('click', function()
	{
		var $container = $(this).closest('.group-content-wrap');
		var $group_items = $container.find('.group-content').children().eq(0);
		var $first = $container.find('.group-prototype').children().eq(0);
		var $clone = $first.clone();
		var $new = $clone.clone();

		$clone.find('input, textarea, select').val('');
		$clone.find('textarea').text('');
		//$clone.show();

		$new
			.appendTo($group_items)
			.hide()
			.fadeIn(700, function () { $(this).css('display','');})

		chemistry.set_dynamic_label($new);

		if ( ! $group_items.hasClass('ui-sortable') || ! $group_items.hasClass('ui-sortable-refreshed'))
		{
			$group_items.sortable({
				handle: '.group-item-title',
				appendTo: 'parent',
				tolerance: 'pointer',
				delay: 100,
				forceHelperSize: true,
				start: function (evt, ui)
				{
					ui.helper.css({width:ui.item.width() + 60});
					ui.placeholder.css({height: ui.item.children().eq(0).height() + 32});
				},
			});
			$group_items.addClass('ui-sortable-refreshed');
		} else
		{
			$group_items.sortable().sortable('refresh');
		}

		check_current_indicator($(this));

		//if ($group_items.children().length === 1)
		{
			$container.children('.buttonset-1').eq(1).fadeIn(500);
		}

		return false;
	});

	$('button.molecule-widget-group-item-remove').live('click', function()
	{
		if (confirm(chemistry.molecule_lang.sure))
		{
			var $container = $(this).closest('.group-content-wrap');
			var $group_items = $container.find('.group-content').children().eq(0);

			$(this).closest('.col')
				.hide(500)
				.queue(function ()
				{
					check_current_indicator($(this));

					if ($group_items.children().length === 1)
					{
						$container.find('.buttonset-1').eq(1).fadeOut(250);
					}

					$(this).remove().dequeue();
				});
		}

		return false;
	});

	// holds original textarea object
	var __RICH_TEXTAREA__ = null;
	var __RICH_CONTENT__ = '';

	$('button.molecule-widget-group-item-rich').live('click', function()
	{
		var $form = $(this).closest('.molecule-widget-content-form');
		var $textarea;
		var $mediabuttons = $('.wp-editor-tools').eq(0).clone();
		var $editor = $('<div class="molecule-widget-content-form molecule-widget-rich-form"><div class="wp-editor-wrap"><div class="wp-editor-container"><textarea name="molecule-rich-content" id="molecule-rich-content" cols="15" class="tinymce"></textarea></div></div></div>');

		$mediabuttons.find('.wp-switch-editor').remove();
		$mediabuttons.children().eq(0).removeAttr('id').removeClass('hide');
		$mediabuttons.find('.add_media').attr('name', 'molecule-rich-content');

		$editor.prepend($mediabuttons);

		$form.after($editor);

		__RICH_TEXTAREA__ = $(this).closest('.group-item').find('textarea').eq(0);

		$textarea = $form.next().find('#molecule-rich-content');
		$textarea.val(__RICH_TEXTAREA__.val());
		$textarea.text(__RICH_TEXTAREA__.val());

		init_richtext($textarea, true);

		$form.hide();

		return false;
	});

	$('.molecule-widget-type-table input[name*="\[rows\]"], .molecule-widget-type-table input[name*="\[columns\]"]').live('change', function(e)
	{
		var $parent = $(this).closest('fieldset');
		var $table = $parent.next('fieldset').find('table.table');
		var name = $table.find('input').eq(0).attr('name');

		var rows = parseInt($parent.find('input[name*="\[rows\]"]').val());
		var columns = parseInt($parent.find('input[name*="\[columns\]"]').val());

		if (rows <= 0 || isNaN(rows))
		{
			rows = 1;
		}

		if (rows > 60)
		{
			rows = 60;
		}

		if (columns <= 0 || isNaN(columns))
		{
			columns = 1;
		}

		if (columns > 30)
		{
			columns = 30;
		}

		$parent.find('input[name*="\[rows\]"]').val(rows);
		$parent.find('input[name*="\[columns\]"]').val(columns);

		var table_content = '';

		var _rows = $table.find('tr').length;
		var _columns = $table.find('tr').eq(0).children('td').length;

		if (rows < _rows)
		{
			$table.html($table.find('tr').slice(0, rows));
		} else if (rows > _rows)
		{
			var $clone = $table.find('tr').eq(0).children('td').eq(0).clone();
			$clone.find('input, select, textarea').val('');
			$clone.find('textarea').text('');

			var diff = rows - _rows;

			if (diff > 0)
			{
				for (var i = 0; i < diff; i++)
				{
					var $tr = $('<tr />');

					for (var j = 0; j < _columns; j++)
					{
						$tr.append($clone.clone());
					}

					$table.append($tr);
				}
			}
		}

		if (columns < _columns)
		{
			$table.find('tr').each( function()
			{
				$(this).html($(this).find('td').slice(0, columns));
			});
		} else if (columns > _columns)
		{
			var $clone = $table.find('tr').eq(0).children('td').eq(0).clone();
			$clone.find('input, select, textarea').val('');
			$clone.find('textarea').text('');

			$table.find('tr').each( function()
			{
				var diff = columns - _columns;

				if (diff > 0)
				{
					for (var i = 0; i < diff; i++)
					{
						$(this).append($clone.clone());
					}
				}
			});
		}

		e.preventDefault();

		return false;
	});

	$('.molecule-widget-type-pricing-table input[name*="\[rows\]"], .molecule-widget-type-pricing-table input[name*="\[columns\]"]').live('change', function(e)
	{
		var $parent = $(this).closest('fieldset');
		var $table = $parent.next('fieldset').find('table.pricing-table-data');
		var $spec_table = $parent.next('fieldset').find('table.pricing-table-header, table.pricing-table-price, table.pricing-table-buttons');
		var name = $table.find('input').eq(0).attr('name');

		var rows = parseInt($parent.find('input[name*="\[rows\]"]').val());
		var columns = parseInt($parent.find('input[name*="\[columns\]"]').val());
		var $highlight = $parent.find('select[name*="\[highlight\]"]').children('option');
		var highlights = parseInt($highlight.length - 1);

		if (rows <= 0 || isNaN(rows))
		{
			rows = 1;
		}

		if (rows > 60)
		{
			rows = 60;
		}

		if (columns <= 0 || isNaN(columns))
		{
			columns = 1;
		}

		if (columns > 10)
		{
			columns = 10;
		}

		$parent.find('input[name*="\[rows\]"]').val(rows);
		$parent.find('input[name*="\[columns\]"]').val(columns);

		var table_content = '';

		var _rows = $table.find('tr:not(:eq(0))').length;
		var _columns = $table.find('tr:not(:eq(0))').eq(0).children('td').length;

		$highlight.not(':eq(0)').remove();

		for (var i = 0; i < columns; i++)
		{
			$highlight.parent().append('<option value="' + (i + 1) + '">' + (i + 1) + '</option>');
		}

		if (rows < _rows)
		{
			$table.html($table.find('tr:not(:eq(0))').slice(0, rows));
		} else if (rows > _rows)
		{
			var $clone = $table.find('tr:not(:eq(0))').eq(0).children('td').eq(0).clone();
			$clone.find('input, select, textarea').val('');
			$clone.find('textarea').text('');

			var diff = rows - _rows;

			if (diff > 0)
			{
				for (var i = 0; i < diff; i++)
				{
					var $tr = $('<tr />');

					for (var j = 0; j < _columns; j++)
					{
						$tr.append($clone.clone());
					}

					$table.append($tr);
				}
			}
		}

		$table.find('th').attr('colspan', columns);
		$spec_table.find('th').attr('colspan', columns);

		if (columns < _columns)
		{
			$table.find('tr:not(:eq(0))').each( function()
			{
				$(this).html($(this).find('td').slice(0, columns));
			});

			$spec_table.each( function()
			{
				$(this).find('tr:not(:eq(0))').each( function()
				{
					$(this).html($(this).find('td').slice(0, columns));
				});
			});
		} else if (columns > _columns)
		{
			var $clone = $table.find('tr:not(:eq(0))').eq(0).children('td').eq(0).clone();
			$clone.find('input, select, textarea').val('');
			$clone.find('textarea').text('');

			$table.find('tr:not(:eq(0))').each( function()
			{
				var diff = columns - _columns;

				if (diff > 0)
				{
					for (var i = 0; i < diff; i++)
					{
						$(this).append($clone.clone());
					}
				}
			});

			$spec_table.each( function()
			{
				var $clone = $(this).find('tr:not(:eq(0))').eq(0).children('td').eq(0).clone();
				$clone.find('input, select, textarea').val('');
				$clone.find('textarea').text('');

				$(this).find('tr:not(:eq(0))').each( function()
				{
					var diff = columns - _columns;

					if (diff > 0)
					{
						for (var i = 0; i < diff; i++)
						{
							$(this).append($clone.clone());
						}
					}
				});
			});
		}
		$parent.next('fieldset').find('td').css({width: 100 / columns + '%'});

		chemistry.set_dynamic_label($parent.next('fieldset'));

		e.preventDefault();

		return false;
	});

	$('.molecule-widget-type-table .save').live('click', function()
	{
		var $parent = $(this).closest('fieldset');
		var $table = $parent.next('fieldset').find('table.table');

		return false;
	});


	//$('.molecule-widget-type-rich-text .edit').live('click', function()
	//{
		/*
		var $textarea = $(this).closest('.molecule-widget').find('textarea');

		if ($textarea.length == 1 && ($textarea.hasClass('tinymce') || $('.widgets-holder-wrap').length > 0))
		{
			init_richtext($textarea);
		}
		*/
	//});

	$('.molecule-widget-type-rich-text .save').live('click', function()
	{
		var $widget = $(this).closest('.molecule-widget-wrapper');
		var $textarea = $widget.find('textarea');
		var val;

		if ($textarea.length == 1 && ($textarea.hasClass('tinymce') || $('.widgets-holder-wrap').length > 0))
		{
			val = tinyMCE.get($textarea.attr('id')).getContent();
			//$textarea.text(tinyMCE.get($textarea.attr('id')).getContent())
			//$textarea.val(tinyMCE.activeEditor.getContent())

			destroy_richtext($textarea);

			$textarea.val(val);
			$textarea.text(val);
		}
	});

	$('.molecule-widget-actions .edit').live('click', function()
	{
		var $slider = $(this).closest('.molecule-widget').find('input[name*="\[slider\]"]');
		var $term = $(this).closest('.molecule-widget').find('select[name*="\[term\]"]');
/*
		if ($slider.length > 0)
		{
			var $target = $slider.parent().nextAll('.cols-3');

			if (typeof $slider.attr('checked') != 'undefined' && $slider.attr('checked') == 'checked')
			{
				$target.eq(0).show();
				$target.eq(1).show();
			} else
			{
				$target.eq(0).hide();
				$target.eq(1).hide();
			}
		}
*/
		/*if ($term.length > 0)
		{
			var $self = $term.parent();
			var $target = $term.closest('fieldset');

			if ($term.val() != '')
			{
				$target.next('.sortable-content').hide();
				$self.next('.cols-3').show();
			} else
			{
				$target.next('.sortable-content').show();
				$self.next('.cols-3').hide();
			}
		}*/

		return false;
	});

	$('.molecule-widget select[name*="\[term\]"]').live('change', function()
	{
		var $self = $(this).parent();
		var $target = $(this).closest('fieldset');

		if ($(this).val() != '')
		{
			$target.next('.sortable-content').hide();
			$self.next('.cols-3').show();
		} else
		{
			$target.next('.sortable-content').show();
			$self.next('.cols-3').hide();
		}
	});

	if (chemistry.hide_visual_tab || (chemistry.hide_visual_tab && chemistry.hide_html_tab))
	{
		chemistry.molecule_tab = true;
	}

	if ($('.postarea').length > 0 && $('#editor-molecule-tab').length > 0)
	{
		if ($('#postdivrich').length == 0 || $('#content-html').length == 0)
		{
			if ($('#postdivrich').length > 0 && $('#content-html').length == 0)
			{
				$('.wp-editor-tools').prepend('<a id="content-html" class="hide-if-no-js wp-switch-editor switch-html active">HTML</a>');
			} else
			{
				$('#editor-toolbar').prepend('<div class="zerosize"><input accesskey="e" type="button" /></div> <a id="edButtonHTML" class="hide-if-no-js active">HTML</a>');
			}
		}

		$('.mceIframeContainer iframe').each( function()
		{
			if ($(this).height() < 50)
			{
				$(this).height(300);
			}
		});

		var $tabs = null;

		if ($('.postarea #editor-toolbar').length > 0)
		{
			$tabs = $('.postarea #editor-toolbar');
		} else if ($('.postarea #wp-content-editor-tools').length > 0)
		{
			$tabs = $('.postarea #wp-content-editor-tools');
		}

		if ($tabs != null)
		{
			$( '#wp-content-media-buttons' ).children('a').eq(0).after($('<a />').attr('id', 'content_editor_button').addClass('hide-if-no-js wp-switch-editor button .insert-media').html( '<span class="wp-media-buttons-icon"></span> Content Editor' ));
			$('#content_editor_button').click( function()
			{
				$('.wp-editor-wrap').removeClass('tmce-active html-active active');
				$('.wp-switch-editor').removeClass('active');
				$(this).addClass('active');
				$('#editor-toolbar #edButtonHTML, #editor-toolbar #edButtonPreview, #wp-content-editor-tools #content-html, #wp-content-editor-tools #content-tmce').removeClass('active');
				$('#editorcontainer, #quicktags, #post-status-info, #media-buttons, #wp-content-media-buttons, #wp-content-editor-container').addClass('hide');
				$('#editor-molecule-tab').removeClass('hide');

				$('input[name=' + chemistry.prefix + 'editor_tab]').val('molecule');

				return false;
			});

			$tabs.children('a:not(#content_editor_button)').click( function()
			{
				if ($(this).attr('id') != 'content_editor_button')
				{
					$('#wp-editor-wrap').removeClass('tmce-active html-active');
					$('.wp-editor-wrap').removeClass('tmce-active html-active active');
					$('.wp-switch-editor').removeClass('active');
					$('#content_editor_button').removeClass('active');

					if ($(this).attr('id') == 'content-html' || $(this).attr('id') == 'edButtonHTML')
					{
						$('#edButtonHTML, #content-html').addClass('active');
						$('.wp-editor-wrap').addClass('html-active');
					} else
					{
						$('#edButtonPreview, #content-tmce').addClass('active');
						$('.wp-editor-wrap').addClass('tmce-active');
					}

					//$(this).addClass('active');
					$('#editorcontainer, #quicktags, #post-status-info, #media-buttons, #wp-content-media-buttons, #wp-content-editor-container').removeClass('hide');
					$('#editor-molecule-tab').addClass('hide');

					$('input[name=' + chemistry.prefix + 'editor_tab]').val('');
				}
			});

			if (chemistry.molecule_tab || $('input[name=' + chemistry.prefix + 'editor_tab]').val() == 'molecule')
			{
				$('#editor-toolbar #edButtonHTML, #editor-toolbar #edButtonPreview, #wp-content-editor-tools #content-html, #wp-content-editor-tools #content-tmce').removeClass('active');
				$('.wp-editor-wrap').removeClass('tmce-active html-active active');
				$('.wp-switch-editor').removeClass('active');
				$('#content_editor_button').addClass('active');
				$('#editorcontainer, #quicktags, #post-status-info, #media-buttons, #wp-content-media-buttons, #wp-content-editor-container').addClass('hide');
				$('#editor-molecule-tab').removeClass('hide');

				$('input[name=' + chemistry.prefix + 'editor_tab]').val('molecule');
			}
		}
	}

	if (chemistry.hide_visual_tab)
	{
		$('#edButtonPreview, .wp-switch-editor.switch-tmce').hide();
	}

	if (chemistry.hide_html_tab)
	{
		$('#edButtonHTML, .wp-switch-editor.switch-html').hide();
	}

	if ( ! chemistry.molecule_tab && (chemistry.hide_visual_tab || chemistry.hide_html_tab))
	{
		$('.wp-editor-wrap').removeClass('tmce-active html-active active');
		$('.wp-switch-editor, #edButtonHTML, #edButtonPreview').removeClass('active');

		if (chemistry.hide_visual_tab)
		{
			/* some kind of bug, shows up tiny mce toolbar so show molecule tab instead */

			/*$('#edButtonHTML, .wp-switch-editor.switch-html').addClass('active');
			$('.wp-editor-wrap').addClass('html-active');
			switchEditors.go('content', 'html');

			$('.mceEditor, #content_parent').addClass('hide');
			$('#content').show();*/
		} else
		{
			$('#edButtonPreview, .wp-switch-editor.switch-tmce').addClass('active');
			$('.wp-editor-wrap').addClass('tmce-active');

			if (typeof switchEditors != 'undefined')
			{
				switchEditors.go('content', 'tinymce');
			}
		}
	}

	$('body').append($('<div />').attr('id', 'molecule-widget-overlay'));
	$('#molecule-widget-overlay').hide();

	$('.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each(make_sortable);

	$(document).keydown( function(e)
	{
		if (e.keyCode == 27)
		{
			chemistry.cancel_molecule_modal();
		}
	});

	var __BUILDER_TARGET__ = null;
	var __BUILDER_POSITION__ = null;

	$('button[name=molecule-widget-add]').live('click', function()
	{
		$('input[name=molecule-widget-filter]').val('');
		$('#molecule-widgets .molecule-widget-wrapper').show();

		$('#molecule-widgets').show();
		$('#molecule-widget-overlay').stop(true, true).fadeTo('fast', 0.75, function()
		{
			$('input[name=molecule-widget-filter]').focus();
		});

		on_molecule_widget_modal_open($('#molecule-widgets'));

		if ($(this).closest('.molecule-widget-core').length > 0)
		{
			var index = $(this).parent('.molecule-widget-column-options').index();
			__BUILDER_TARGET__ = $(this).closest('.molecule-widget').children('.molecule-widget-row').children('.molecule-widget-column').eq(index);
			__BUILDER_POSITION__ = 'append';
		} else if ($(this).closest('.molecule-location-wrapper').length > 0)
		{
			__BUILDER_TARGET__ = $('.molecule-location-wrapper:not(.read-only) #molecule-location-main');

			if ($(this).closest('.buttonset-1').next('.molecule-location-wrapper:not(.read-only) #molecule-location-main').length == 1)
			{
				__BUILDER_POSITION__ = 'prepend';
			} else
			{
				__BUILDER_POSITION__ = 'append';
			}
		}

		return false;
	});

	$('#molecule-widgets .molecule-widget-wrapper').click( function()
	{
		var $clone = $(this).clone();

		$clone.addClass('chemistry-initial');

		if (__BUILDER_TARGET__ != null)
		{
			if ($clone.hasClass('molecule-widget-core'))
			{
				__BUILDER_TARGET__ = $('.molecule-location-wrapper:not(.read-only) #molecule-location-main');
			}

			if (__BUILDER_POSITION__ == 'prepend')
			{
				__BUILDER_TARGET__.prepend($clone);
				update_widget_data($clone);
				update_widget_row_order($('.molecule-location .molecule-widget-wrapper'));
			} else
			{
				__BUILDER_TARGET__.append($clone);
				update_widget_data($clone);
			}

			$('#molecule-widgets').hide();
			$('#molecule-widget-overlay').stop(true, true).fadeTo('fast', 0.0, function() { $(this).hide(); });

			if ( ! $clone.hasClass('molecule-widget-core'))
			{
				$clone.find('a.edit').click();
			} else
			{
				//$clone.find('.molecule-widget-row-options').hide();
				$('.molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each( function()
				{
					$(this).sortable().sortable('enable');
				});
			}

			$('.molecule-location-wrapper:not(.read-only) .molecule-location, .molecule-location-wrapper:not(.read-only) .molecule-location .molecule-widget-row > div.molecule-widget-column').each(make_sortable);
		}
	});

	$('.molecule-widget-actions a.duplicate').live('click', function()
	{
		make_duplicate($(this).parents('.molecule-widget-wrapper').eq(0), true, true);

		return false;
	});

	$('.molecule-widget-actions a.edit').live('click', function()
	{
		var $widget = $(this).parents('.molecule-widget-wrapper').eq(0);

		$(this).find('.molecule-widget-content').addClass('closed');
		$('#molecule-widget-overlay').stop(true, true).fadeTo('fast', 0.75);

		$(this).closest('.molecule-widget').find('.molecule-widget-content').toggleClass('closed');
		$(this).closest('.molecule-widget').find('input[type!=hidden], select, textarea').eq(0).focus();

		on_molecule_widget_modal_open($widget);

		return false;
	});

	$('.molecule-widget .save').live('click', function()
	{
		var $widget = $(this).closest('.molecule-widget-wrapper');

		//this is a marker class for widgets that are just added from #molecule-widgets, it gets removed as soon as a wigdet is saved for the first time
		$widget.removeClass('chemistry-initial');

		if ($widget.find('.molecule-widget-rich-form').length > 0)
		{
			var $textarea = $widget.find('#molecule-rich-content');

			destroy_richtext($textarea, true);

			__RICH_TEXTAREA__.val(__RICH_CONTENT__);
			__RICH_TEXTAREA__.text(__RICH_CONTENT__);

			$widget.find('.molecule-widget-rich-form').remove();
			$widget.find('.molecule-widget-content-form').show();

			return false;
		}

		update_excerpt($widget.find('.molecule-widget'));

		$('.molecule-widget-content').addClass('closed');
		$('#molecule-widget-overlay').stop(true, true).fadeTo('fast', 0.0, function() { $(this).hide(); });

		var $sidebar_inside = $(this).closest('.widget-inside');

		if ($sidebar_inside.length > 0)
		{
			$sidebar_inside.find('input[name=savewidget]').click();
		}

		on_molecule_widget_modal_close();

		return false;
	});

	$('.molecule-modal-close').live('click', function (evt)
	{
		chemistry.cancel_molecule_modal(evt);
	});

	$('.molecule-location-wrapper:not(.read-only) .molecule-widget .remove').live('click', function()
	{
		chemistry.remove_widget($(this).closest('.molecule-widget-wrapper'));
	});

	$('.molecule-location .molecule-widget').each( function()
	{
		update_excerpt($(this));
	});

	$('#molecule-widgets .hidden-widgets-show a').click( function()
	{
		$('#molecule-widgets').find('.molecule-widget-wrapper.hide').removeClass('hide').removeAttr('style');
		$('#molecule-widgets .hidden-widgets-show, #molecule-widgets .hidden-widgets-count').remove();

		return false;
	});

	$('.molecule-options > p > a').click( function()
	{
		var index = $(this).index();

		var $current = $(this).parent().nextAll('fieldset').eq(index);
		$(this).parent().parent().find('fieldset').not($current).slideUp();

		$current.stop(true, true).slideDown();

		return false;
	});

	$('.checkbox-group-toggle-button').click(function ()
	{
		var ref_id = $(this).attr('class').match(/checkbox-group-ref-\d/)[0];
		var state = $(this).attr('data-checkbox-group-state');
		$(this).attr('data-checkbox-group-state', state == 0 ? 1 : 0);

		if (state == 0)
		{
			$(this).text('Deselect All');
			$('#' + ref_id).find('input[type="checkbox"]').prop('checked', true);
		} else
		{
			$(this).text('Select All');
			$('#' + ref_id).find('input[type="checkbox"]').prop('checked', false);
		}
		$(this).toggleClass('icon-check icon-uncheck');
	});

	$('.molecule-widget select').live('change', function ()
	{
		var val = $(this).val();

		$(this)
			.children('option[value="' + val + '"]').siblings().removeAttr('selected').end()
			.attr('selected', 'selected');
	});

	$('.molecule-widget input').live('keydown', function (evt)
	{
		if (evt.keyCode === 13)
		{
			//$(this).blur();
			return false;
		}
	});

	$('textarea').live('blur', function ()
	{
		var val = $(this).val();
		$(this).val(val);
		$(this).text(val);
	});

	$('.molecule-location .molecule-widget-wrapper').live('mousedown', function ()
	{
		chemistry.widget_mousedown = true;
	}).live('mouseup', function ()
	{
		chemistry.widget_mousedown = false;
	});

	chemistry.set_tabs($('#form-options'));

	$(window).resize(function ()
	{
		if (chemistry.$current_molecule_widget_modal && chemistry.$current_molecule_widget_modal.css('display') !== 'none')
		{
			clearTimeout(chemistry.set_dynamic_label_timeout);
			chemistry.set_dynamic_label_timeout = setTimeout(function ()
			{
				chemistry.set_dynamic_label(chemistry.$current_molecule_widget_modal);
				delete chemistry.set_dynamic_label_timeout;
			}, 250);
		}
	});

});})(jQuery);
