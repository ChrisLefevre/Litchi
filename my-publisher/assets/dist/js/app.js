'use strict';


$.SwEditor = {};
$.SwEditor.options = {
	navbarMenuSlimscroll: true,
	navbarMenuSlimscrollWidth: "3px",
	navbarMenuHeight: "200px",
	sidebarToggleSelector: "[data-toggle='offcanvas']",
	sidebarPushMenu: true,
	sidebarSlimScroll: true,
	sidebarExpandOnHover: false,
	enableBoxRefresh: true,
	enableBSToppltip: true,
	BSTooltipSelector: "[data-toggle='tooltip']",
	enableFastclick: true,
	enableControlSidebar: true,
	controlSidebarOptions: {
		toggleBtnSelector: "[data-toggle='control-sidebar']",
		selector: ".control-sidebar",
		slide: true
	},
	enableBoxWidget: true,
	boxWidgetOptions: {
		boxWidgetIcons: {
			collapse: 'fa-minus',
			open: 'fa-plus',
			remove: 'fa-times'
		},
		boxWidgetSelectors: {
			remove: '[data-widget="remove"]',
			collapse: '[data-widget="collapse"]'
		}
	},
	directChat: {
		enable: true,
		contactToggleSelector: '[data-widget="chat-pane-toggle"]'
	},
	colors: {
		lightBlue: "#3c8dbc",
		red: "#f56954",
		green: "#00a65a",
		aqua: "#00c0ef",
		yellow: "#f39c12",
		blue: "#0073b7",
		navy: "#001F3F",
		teal: "#39CCCC",
		olive: "#3D9970",
		lime: "#01FF70",
		orange: "#FF851B",
		fuchsia: "#F012BE",
		purple: "#8E24AA",
		maroon: "#D81B60",
		black: "#222222",
		gray: "#d2d6de"
	},
	screenSizes: {
		xs: 480,
		sm: 768,
		md: 992,
		lg: 1200
	}
};
$(function() {
	
	$('.summernote').summernote({
		height: 200
	});

	$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
	}); 
	
	
	if (typeof SwEditorOptions !== "undefined") {
		$.extend(true, $.SwEditor.options, SwEditorOptions);
	}
	var o = $.SwEditor.options;
	_init();
	$.SwEditor.layout.activate();
	$.SwEditor.tree('.sidebar');
	if (o.enableControlSidebar) {
		$.SwEditor.controlSidebar.activate();
	}
	if (o.navbarMenuSlimscroll && typeof $.fn.slimscroll != 'undefined') {
		$(".navbar .menu").slimscroll({
			height: o.navbarMenuHeight,
			alwaysVisible: false,
			size: o.navbarMenuSlimscrollWidth
		}).css("width", "100%");
	}
	if (o.sidebarPushMenu) {
		$.SwEditor.pushMenu.activate(o.sidebarToggleSelector);
	}
	if (o.enableBSToppltip) {
		$('body').tooltip({
			selector: o.BSTooltipSelector
		});
	}
	if (o.enableBoxWidget) {
		$.SwEditor.boxWidget.activate();
	}
	if (o.enableFastclick && typeof FastClick != 'undefined') {
		FastClick.attach(document.body);
	}
	if (o.directChat.enable) {
		$(o.directChat.contactToggleSelector).on('click', function() {
			var box = $(this).parents('.direct-chat').first();
			box.toggleClass('direct-chat-contacts-open');
		});
	}
	$('.btn-group[data-toggle="btn-toggle"]').each(function() {
		var group = $(this);
		$(this).find(".btn").on('click', function(e) {
			group.find(".btn.active").removeClass("active");
			$(this).addClass("active");
			e.preventDefault();
		});
	});
});


function convdate(fdid) {
	
	var dt = $('#'+fdid+'_d').val();
	var tt = $('#'+fdid+'_t').val();
	    dt = dt.split('/');
		var fdate =  dt.reverse().join('-')+' '+tt+':00';
		$('#'+fdid).val(fdate);
	
	//var ndt = Date.parseExact(dt, 'dd/MM/yyyy').toString('yyyy-MM-dd');
	
	console.log(fdate);
	
}



function _init() {
	$.SwEditor.layout = {
		activate: function() {
			var _this = this;
			_this.fix();
			_this.fixSidebar();
			$(window, ".wrapper").resize(function() {
				_this.fix();
				_this.fixSidebar();
			});
		},
		fix: function() {
			var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight();
			var window_height = $(window).height();
			var sidebar_height = $(".sidebar").height();
			if ($("body").hasClass("fixed")) {
				$(".content-wrapper, .right-side").css('min-height', window_height - $('.main-footer').outerHeight());
			} else {
				var postSetWidth;
				if (window_height >= sidebar_height) {
					$(".content-wrapper, .right-side").css('min-height', window_height - neg);
					postSetWidth = window_height - neg;
				} else {
					$(".content-wrapper, .right-side").css('min-height', sidebar_height);
					postSetWidth = sidebar_height;
				}
				var controlSidebar = $($.SwEditor.options.controlSidebarOptions.selector);
				if (typeof controlSidebar !== "undefined") {
					if (controlSidebar.height() > postSetWidth) $(".content-wrapper, .right-side").css('min-height', controlSidebar.height());
				}
			}
		},
		fixSidebar: function() {
			if (!$("body").hasClass("fixed")) {
				if (typeof $.fn.slimScroll != 'undefined') {
					$(".sidebar").slimScroll({
						destroy: true
					}).height("auto");
				}
				return;
			} else if (typeof $.fn.slimScroll == 'undefined' && console) {
				console.error("Error: the fixed layout requires the slimscroll plugin!");
			}
			if ($.SwEditor.options.sidebarSlimScroll) {
				if (typeof $.fn.slimScroll != 'undefined') {
					$(".sidebar").slimScroll({
						destroy: true
					}).height("auto");
					$(".sidebar").slimscroll({
						height: ($(window).height() - $(".main-header").height()) + "px",
						color: "rgba(0,0,0,0.2)",
						size: "3px"
					});
				}
			}
		}
	};
	$.SwEditor.pushMenu = {
		activate: function(toggleBtn) {
			var screenSizes = $.SwEditor.options.screenSizes;
			$(toggleBtn).on('click', function(e) {
				e.preventDefault();
				if ($(window).width() > (screenSizes.sm - 1)) {
					$("body").toggleClass('sidebar-collapse');
				} else {
					if ($("body").hasClass('sidebar-open')) {
						$("body").removeClass('sidebar-open');
						$("body").removeClass('sidebar-collapse')
					} else {
						$("body").addClass('sidebar-open');
					}
				}
			});
			$(".content-wrapper").click(function() {
				if ($(window).width() <= (screenSizes.sm - 1) && $("body").hasClass("sidebar-open")) {
					$("body").removeClass('sidebar-open');
				}
			});
			if ($.SwEditor.options.sidebarExpandOnHover || ($('body').hasClass('fixed') && $('body').hasClass('sidebar-mini'))) {
				this.expandOnHover();
			}
		},
		expandOnHover: function() {
			var _this = this;
			var screenWidth = $.SwEditor.options.screenSizes.sm - 1;
			$('.main-sidebar').hover(function() {
				if ($('body').hasClass('sidebar-mini') && $("body").hasClass('sidebar-collapse') && $(window).width() > screenWidth) {
					_this.expand();
				}
			}, function() {
				if ($('body').hasClass('sidebar-mini') && $('body').hasClass('sidebar-expanded-on-hover') && $(window).width() > screenWidth) {
					_this.collapse();
				}
			});
		},
		expand: function() {
			$("body").removeClass('sidebar-collapse').addClass('sidebar-expanded-on-hover');
		},
		collapse: function() {
			if ($('body').hasClass('sidebar-expanded-on-hover')) {
				$('body').removeClass('sidebar-expanded-on-hover').addClass('sidebar-collapse');
			}
		}
	};
	$.SwEditor.tree = function(menu) {
		var _this = this;
		$("li a", $(menu)).on('click', function(e) {
			var $this = $(this);
			var checkElement = $this.next();
			if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible'))) {
				checkElement.slideUp('normal', function() {
					checkElement.removeClass('menu-open');
				});
				checkElement.parent("li").removeClass("active");
			} else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
				var parent = $this.parents('ul').first();
				var ul = parent.find('ul:visible').slideUp('normal');
				ul.removeClass('menu-open');
				var parent_li = $this.parent("li");
				checkElement.slideDown('normal', function() {
					checkElement.addClass('menu-open');
					parent.find('li.active').removeClass('active');
					parent_li.addClass('active');
					_this.layout.fix();
				});
			}
			if (checkElement.is('.treeview-menu')) {
				e.preventDefault();
			}
		});
	};
	$.SwEditor.controlSidebar = {
		activate: function() {
			var _this = this;
			var o = $.SwEditor.options.controlSidebarOptions;
			var sidebar = $(o.selector);
			var btn = $(o.toggleBtnSelector);
			btn.on('click', function(e) {
				e.preventDefault();
				if (!sidebar.hasClass('control-sidebar-open') && !$('body').hasClass('control-sidebar-open')) {
					_this.open(sidebar, o.slide);
				} else {
					_this.close(sidebar, o.slide);
				}
			});
			var bg = $(".control-sidebar-bg");
			_this._fix(bg);
			if ($('body').hasClass('fixed')) {
				_this._fixForFixed(sidebar);
			} else {
				if ($('.content-wrapper, .right-side').height() < sidebar.height()) {
					_this._fixForContent(sidebar);
				}
			}
		},
		open: function(sidebar, slide) {
			var _this = this;
			if (slide) {
				sidebar.addClass('control-sidebar-open');
			} else {
				$('body').addClass('control-sidebar-open');
			}
		},
		close: function(sidebar, slide) {
			if (slide) {
				sidebar.removeClass('control-sidebar-open');
			} else {
				$('body').removeClass('control-sidebar-open');
			}
		},
		_fix: function(sidebar) {
			var _this = this;
			if ($("body").hasClass('layout-boxed')) {
				sidebar.css('position', 'absolute');
				sidebar.height($(".wrapper").height());
				$(window).resize(function() {
					_this._fix(sidebar);
				});
			} else {
				sidebar.css({
					'position': 'fixed',
					'height': 'auto'
				});
			}
		},
		_fixForFixed: function(sidebar) {
			sidebar.css({
				'position': 'fixed',
				'max-height': '100%',
				'overflow': 'auto',
				'padding-bottom': '50px'
			});
		},
		_fixForContent: function(sidebar) {
			$(".content-wrapper, .right-side").css('min-height', sidebar.height());
		}
	};
	$.SwEditor.boxWidget = {
		selectors: $.SwEditor.options.boxWidgetOptions.boxWidgetSelectors,
		icons: $.SwEditor.options.boxWidgetOptions.boxWidgetIcons,
		activate: function() {
			var _this = this;
			$(_this.selectors.collapse).on('click', function(e) {
				e.preventDefault();
				_this.collapse($(this));
			});
			$(_this.selectors.remove).on('click', function(e) {
				e.preventDefault();
				_this.remove($(this));
			});
		},
		collapse: function(element) {
			var _this = this;
			var box = element.parents(".box").first();
			var box_content = box.find("> .box-body, > .box-footer");
			if (!box.hasClass("collapsed-box")) {
				element.children(":first").removeClass(_this.icons.collapse).addClass(_this.icons.open);
				box_content.slideUp(300, function() {
					box.addClass("collapsed-box");
				});
			} else {
				element.children(":first").removeClass(_this.icons.open).addClass(_this.icons.collapse);
				box_content.slideDown(300, function() {
					box.removeClass("collapsed-box");
				});
			}
		},
		remove: function(element) {
			var box = element.parents(".box").first();
			box.slideUp();
		}
	};
}




function SetTempFormModal(ch) {
$("#form_temp_source").val(ch);
$("#form_temp_text").val($(ch).val());
$('#modal-temp').modal('show');
}


function GetTempFormModal() {
var _v = $("#form_temp_source").val();
$(_v).val($("#form_temp_text").val());
$('#modal-temp').modal('hide');
}

