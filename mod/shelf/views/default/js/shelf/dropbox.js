/*
 * Shelf Dropbox
 *
 */
if (jQuery) (function ($) {
    $.extend($.fn, {
        Dropbox: function (method, data) {

            switch (method) {
                case 'show':
                    show(null, $(this));
                    return $(this);
                case 'open':
                    open(null, $(this));
                    return $(this);
                case 'hide_xxx':
                    hide();
                    return $(this);
                case 'attach':
                    return $(this).attr('data-dropbox', data);
                case 'detach':
                    hide();
                    return $(this).removeAttr('data-dropbox');
                case 'disable':
                    return $(this).addClass('dropbox-disabled');
                case 'enable':
                    hide();
                    return $(this).removeClass('dropbox-disabled');
            }

        }
    });

    function show(event, object) {

        var trigger = event ? $(this) : object,
            Dropbox = $(trigger.attr('data-dropbox')),
            isOpen = trigger.hasClass('dropbox-open');
        console.log('dropbox.js>show pallet: '+trigger.attr('data-dropbox'));

        // In some cases we don't want to show it
        if (event) {
            if ($(event.target).hasClass('dropbox-ignore')) return;

            event.preventDefault();
            event.stopPropagation();
        } else {
            if (trigger !== object.target && $(object.target).hasClass('dropbox-ignore')) return;
        }
        hide();

        if (isOpen || trigger.hasClass('dropbox-disabled')) return;

        // Show it
        trigger.addClass('dropbox-open');
        Dropbox
            .data('dropbox-trigger', trigger)
            .show();

        // Position it
        position();

        // Trigger the show callback
        Dropbox
            .trigger('show', {
                Dropbox: Dropbox,
                trigger: trigger
            });

    }

    function open(event, object) {

        var trigger = event ? $(this) : object,
            Dropbox = $(trigger.attr('data-dropbox')),
            isOpen = trigger.hasClass('dropbox-open');
        console.log('dropbox.js>open pallet: '+trigger.attr('data-dropbox'));

        // In some cases we don't want to show it
        if (event) {
            if ($(event.target).hasClass('dropbox-ignore')) return;

            event.preventDefault();
            event.stopPropagation();
        } else {
            if (trigger !== object.target && $(object.target).hasClass('dropbox-ignore')) return;
        }
        hide();

        if (isOpen || trigger.hasClass('dropbox-disabled')) return;

        // Show it
        trigger.addClass('dropbox-open');
        console.log('dropbox.js>open After Show it');
        Dropbox
            .data('dropbox-trigger', trigger)
            .show();

        // Position it
//        position();

        // Trigger the show callback
        Dropbox
            .trigger('open', {
                Dropbox: Dropbox,
                trigger: trigger
            });

    }
    function hide(event) {

        // In some cases we don't hide them
        var targetGroup = event ? $(event.target).parents().addBack() : null;

        // Are we clicking anywhere in a dropbox?
        if (targetGroup && targetGroup.is('.dropbox')) {
            // Is it a dropbox menu?
            if (targetGroup.is('.dropbox-menu')) {
                // Did we click on an option? If so close it.
                if (!targetGroup.is('A')) return;
            } else {
                // Nope, it's a panel. Leave it open.
                return;
            }
        }

        // Trigger the event early, so that it might be prevented on the visible popups
        var hideEvent = jQuery.Event("hide");

        $(document).find('.dropbox:visible').each(function () {
            var Dropbox = $(this);
            Dropbox
                .hide()
                .removeData('dropbox-trigger')
                .trigger('hide', { Dropbox: Dropbox });
        });

        if(!hideEvent.isDefaultPrevented()) {
            // Hide any dropbox that may be showing
            $(document).find('.dropbox:visible').each(function () {
                var Dropbox = $(this);
                Dropbox
                    .hide()
                    .removeData('dropbox-trigger')
                    .trigger('hide', { Dropbox: Dropbox });
            });

            // Remove all dropbox-open classes
            $(document).find('.dropbox-open').removeClass('dropbox-open');
        }
    }

    function position() {

        var Dropbox = $('.dropbox:visible').eq(0),
            trigger = Dropbox.data('dropbox-trigger'),
            hOffset = trigger ? parseInt(trigger.attr('data-horizontal-offset') || 0, 10) : null,
            vOffset = trigger ? parseInt(trigger.attr('data-vertical-offset') || 0, 10) : null,
//@EDIT 2018-06-11 - SAJ - receive fixed positioning values
    		hPosition = trigger ? parseInt(trigger.attr('data-horizontal-fixed') || 0, 10) : null,
    	    vPosition = trigger ? parseInt(trigger.attr('data-vertical-fixed') || 0, 10) : null;
            
        if (Dropbox.length === 0 || !trigger) return;

        // Position the dropbox relative-to-parent...
        if (Dropbox.hasClass('dropbox-relative')) {
            Dropbox.css({
                left: Dropbox.hasClass('dropbox-anchor-right') ?
                    trigger.position().left - (Dropbox.outerWidth(true) - trigger.outerWidth(true)) - parseInt(trigger.css('margin-right'), 10) + hOffset :
                    trigger.position().left + parseInt(trigger.css('margin-left'), 10) + hOffset,
                top: Dropbox.hasClass('dropbox-anchor-top') ?
                	 trigger.position().top + vOffset: 
                     trigger.position().top + trigger.outerHeight(true) - parseInt(trigger.css('margin-top'), 10) + vOffset
            });
        } else {
            // ...or relative to document
            Dropbox.css({
                left: Dropbox.hasClass('dropbox-anchor-right') ?
                    trigger.offset().left - (Dropbox.outerWidth() - trigger.outerWidth()) + hOffset : trigger.offset().left + hOffset,
                top: trigger.offset().top + trigger.outerHeight() + vOffset
            });
        }
//@EDIT 2018-06-11 - SAJ - override positioning if fixed positioning values received
        if (hPosition != null || hPosition === 0){
        	Dropbox.css({
                left: hPosition
        	});
        }
    	if (vPosition){
        	Dropbox.css({
        		top: vPosition
        	});
    	}
    }

//    $(document).on('click.dropbox', '[data-dropbox]', show);
    $(document).on('click.dropbox', hide);
    $(window).on('resize', position);

})(jQuery);