<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$maintenance = file_get_contents("udrzba/status.txt");
$ip=$_SERVER["REMOTE_ADDR"];
$whitelist = file_get_contents("udrzba/whitelist.txt");
$whitelist = explode("\n", $whitelist);
if($maintenance == 1 && !in_array($ip,$whitelist)){
    header("Location: /udrzba/");
}
$root_url = file_get_contents("domain.txt");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Chochrun.eu</title>
    <link href="http://fonts.googleapis.com/css?family=Oswald:400,700,300" rel="stylesheet" type="text/css" />
    <!-- Included CSS Files -->
    <link rel="stylesheet" href="stylesheets/main.css" />
    <link rel="stylesheet" href="stylesheets/devices.css" />
    <link rel="stylesheet" href="stylesheets/paralax_slider.css" />
    <link rel="stylesheet" href="stylesheets/post.css" type="text/css" media="screen" title="no title" charset="utf-8" />
    <link rel="stylesheet" href="stylesheets/jquery.fancybox1c51.css?v=2.1.2" type="text/css"  media="screen" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <script src="http://code.jquery.com/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script>
        /*
         *	jQuery carouFredSel 6.1.0
         *	Demo's and documentation:
         *	caroufredsel.frebsite.nl
         *
         *	Copyright (c) 2012 Fred Heusschen
         *	www.frebsite.nl
         *
         *	Dual licensed under the MIT and GPL licenses.
         *	http://en.wikipedia.org/wiki/MIT_License
         *	http://en.wikipedia.org/wiki/GNU_General_Public_License
         */


        (function($) {


            //	LOCAL

            if ( $.fn.carouFredSel )
            {
                return;
            }

            $.fn.caroufredsel = $.fn.carouFredSel = function(options, configs)
            {

                //	no element
                if (this.length == 0)
                {
                    debug( true, 'No element found for "' + this.selector + '".' );
                    return this;
                }

                //	multiple elements
                if (this.length > 1)
                {
                    return this.each(function() {
                        $(this).carouFredSel(options, configs);
                    });
                }


                var $cfs = this,
                    $tt0 = this[0],
                    starting_position = false;

                if ($cfs.data('_cfs_isCarousel'))
                {
                    starting_position = $cfs.triggerHandler('_cfs_triggerEvent', 'currentPosition');
                    $cfs.trigger('_cfs_triggerEvent', ['destroy', true]);
                }


                $cfs._cfs_init = function(o, setOrig, start)
                {
                    o = go_getObject($tt0, o);

                    o.items = go_getItemsObject($tt0, o.items);
                    o.scroll = go_getScrollObject($tt0, o.scroll);
                    o.auto = go_getAutoObject($tt0, o.auto);
                    o.prev = go_getPrevNextObject($tt0, o.prev);
                    o.next = go_getPrevNextObject($tt0, o.next);
                    o.pagination = go_getPaginationObject($tt0, o.pagination);
                    o.swipe = go_getSwipeObject($tt0, o.swipe);
                    o.mousewheel = go_getMousewheelObject($tt0, o.mousewheel);

                    if (setOrig)
                    {
                        opts_orig = $.extend(true, {}, $.fn.carouFredSel.defaults, o);
                    }

                    opts = $.extend(true, {}, $.fn.carouFredSel.defaults, o);
                    opts.d = cf_getDimensions(opts);

                    crsl.direction = (opts.direction == 'up' || opts.direction == 'left') ? 'next' : 'prev';

                    var	a_itm = $cfs.children(),
                        avail_primary = ms_getParentSize($wrp, opts, 'width');

                    if (is_true(opts.cookie))
                    {
                        opts.cookie = 'caroufredsel_cookie_' + conf.serialNumber;
                    }

                    opts.maxDimension = ms_getMaxDimension(opts, avail_primary);

                    //	complement items and sizes
                    opts.items = in_complementItems(opts.items, opts, a_itm, start);
                    opts[opts.d['width']] = in_complementPrimarySize(opts[opts.d['width']], opts, a_itm);
                    opts[opts.d['height']] = in_complementSecondarySize(opts[opts.d['height']], opts, a_itm);

                    //	primary size not set for a responsive carousel
                    if (opts.responsive)
                    {
                        if (!is_percentage(opts[opts.d['width']]))
                        {
                            opts[opts.d['width']] = '100%';
                        }
                    }

                    //	primary size is percentage
                    if (is_percentage(opts[opts.d['width']]))
                    {
                        crsl.upDateOnWindowResize = true;
                        crsl.primarySizePercentage = opts[opts.d['width']];
                        opts[opts.d['width']] = ms_getPercentage(avail_primary, crsl.primarySizePercentage);
                        if (!opts.items.visible)
                        {
                            opts.items.visibleConf.variable = true;
                        }
                    }

                    if (opts.responsive)
                    {
                        opts.usePadding = false;
                        opts.padding = [0, 0, 0, 0];
                        opts.align = false;
                        opts.items.visibleConf.variable = false;
                    }
                    else
                    {
                        //	visible-items not set
                        if (!opts.items.visible)
                        {
                            opts = in_complementVisibleItems(opts, avail_primary);
                        }

                        //	primary size not set -> calculate it or set to "variable"
                        if (!opts[opts.d['width']])
                        {
                            if (!opts.items.visibleConf.variable && is_number(opts.items[opts.d['width']]) && opts.items.filter == '*')
                            {
                                opts[opts.d['width']] = opts.items.visible * opts.items[opts.d['width']];
                                opts.align = false;
                            }
                            else
                            {
                                opts[opts.d['width']] = 'variable';
                            }
                        }
                        //	align not set -> set to center if primary size is number
                        if (is_undefined(opts.align))
                        {
                            opts.align = (is_number(opts[opts.d['width']]))
                                ? 'center'
                                : false;
                        }
                        //	set variabe visible-items
                        if (opts.items.visibleConf.variable)
                        {
                            opts.items.visible = gn_getVisibleItemsNext(a_itm, opts, 0);
                        }
                    }

                    //	set visible items by filter
                    if (opts.items.filter != '*' && !opts.items.visibleConf.variable)
                    {
                        opts.items.visibleConf.org = opts.items.visible;
                        opts.items.visible = gn_getVisibleItemsNextFilter(a_itm, opts, 0);
                    }

                    opts.items.visible = cf_getItemsAdjust(opts.items.visible, opts, opts.items.visibleConf.adjust, $tt0);
                    opts.items.visibleConf.old = opts.items.visible;

                    if (opts.responsive)
                    {
                        if (!opts.items.visibleConf.min)
                        {
                            opts.items.visibleConf.min = opts.items.visible;
                        }
                        if (!opts.items.visibleConf.max)
                        {
                            opts.items.visibleConf.max = opts.items.visible;
                        }
                        opts = in_getResponsiveValues(opts, a_itm, avail_primary);
                    }
                    else
                    {
                        opts.padding = cf_getPadding(opts.padding);

                        if (opts.align == 'top')
                        {
                            opts.align = 'left';
                        }
                        else if (opts.align == 'bottom')
                        {
                            opts.align = 'right';
                        }

                        switch (opts.align)
                        {
                            //	align: center, left or right
                            case 'center':
                            case 'left':
                            case 'right':
                                if (opts[opts.d['width']] != 'variable')
                                {
                                    opts = in_getAlignPadding(opts, a_itm);
                                    opts.usePadding = true;
                                }
                                break;

                            //	padding
                            default:
                                opts.align = false;
                                opts.usePadding = (
                                    opts.padding[0] == 0 &&
                                    opts.padding[1] == 0 &&
                                    opts.padding[2] == 0 &&
                                    opts.padding[3] == 0
                                ) ? false : true;
                                break;
                        }
                    }

                    if (!is_number(opts.scroll.duration))
                    {
                        opts.scroll.duration = 500;
                    }
                    if (is_undefined(opts.scroll.items))
                    {
                        opts.scroll.items = (opts.responsive || opts.items.visibleConf.variable || opts.items.filter != '*')
                            ? 'visible'
                            : opts.items.visible;
                    }

                    opts.auto = $.extend(true, {}, opts.scroll, opts.auto);
                    opts.prev = $.extend(true, {}, opts.scroll, opts.prev);
                    opts.next = $.extend(true, {}, opts.scroll, opts.next);
                    opts.pagination = $.extend(true, {}, opts.scroll, opts.pagination);
                    //	swipe and mousewheel extend later on, per direction

                    opts.auto = go_complementAutoObject($tt0, opts.auto);
                    opts.prev = go_complementPrevNextObject($tt0, opts.prev);
                    opts.next = go_complementPrevNextObject($tt0, opts.next);
                    opts.pagination = go_complementPaginationObject($tt0, opts.pagination);
                    opts.swipe = go_complementSwipeObject($tt0, opts.swipe);
                    opts.mousewheel = go_complementMousewheelObject($tt0, opts.mousewheel);

                    if (opts.synchronise)
                    {
                        opts.synchronise = cf_getSynchArr(opts.synchronise);
                    }


                    //	DEPRECATED
                    if (opts.auto.onPauseStart)
                    {
                        opts.auto.onTimeoutStart = opts.auto.onPauseStart;
                        deprecated('auto.onPauseStart', 'auto.onTimeoutStart');
                    }
                    if (opts.auto.onPausePause)
                    {
                        opts.auto.onTimeoutPause = opts.auto.onPausePause;
                        deprecated('auto.onPausePause', 'auto.onTimeoutPause');
                    }
                    if (opts.auto.onPauseEnd)
                    {
                        opts.auto.onTimeoutEnd = opts.auto.onPauseEnd;
                        deprecated('auto.onPauseEnd', 'auto.onTimeoutEnd');
                    }
                    if (opts.auto.pauseDuration)
                    {
                        opts.auto.timeoutDuration = opts.auto.pauseDuration;
                        deprecated('auto.pauseDuration', 'auto.timeoutDuration');
                    }
                    //	/DEPRECATED


                };	//	/init


                $cfs._cfs_build = function() {
                    $cfs.data('_cfs_isCarousel', true);

                    var a_itm = $cfs.children(),
                        orgCSS = in_mapCss($cfs, ['textAlign', 'float', 'position', 'top', 'right', 'bottom', 'left', 'zIndex', 'width', 'height', 'marginTop', 'marginRight', 'marginBottom', 'marginLeft']),
                        newPosition = 'relative';

                    switch (orgCSS.position)
                    {
                        case 'absolute':
                        case 'fixed':
                            newPosition = orgCSS.position;
                            break;
                    }

                    $wrp.css(orgCSS).css({
                        'overflow'		: 'hidden',
                        'position'		: newPosition
                    });

                    $cfs.data('_cfs_origCss', orgCSS).css({
                        'textAlign'		: 'left',
                        'float'			: 'none',
                        'position'		: 'absolute',
                        'top'			: 0,
                        'right'			: 'auto',
                        'bottom'		: 'auto',
                        'left'			: 0,
                        'marginTop'		: 0,
                        'marginRight'	: 0,
                        'marginBottom'	: 0,
                        'marginLeft'	: 0
                    });

                    sz_storeMargin(a_itm, opts);
                    sz_storeSizes(a_itm, opts);
                    if (opts.responsive)
                    {
                        sz_setResponsiveSizes(opts, a_itm);
                    }

                };	//	/build


                $cfs._cfs_bind_events = function() {
                    $cfs._cfs_unbind_events();


                    //	stop event
                    $cfs.bind(cf_e('stop', conf), function(e, imm) {
                        e.stopPropagation();

                        //	button
                        if (!crsl.isStopped)
                        {
                            if (opts.auto.button)
                            {
                                opts.auto.button.addClass(cf_c('stopped', conf));
                            }
                        }

                        //	set stopped
                        crsl.isStopped = true;

                        if (opts.auto.play)
                        {
                            opts.auto.play = false;
                            $cfs.trigger(cf_e('pause', conf), imm);
                        }
                        return true;
                    });


                    //	finish event
                    $cfs.bind(cf_e('finish', conf), function(e) {
                        e.stopPropagation();
                        if (crsl.isScrolling)
                        {
                            sc_stopScroll(scrl);
                        }
                        return true;
                    });


                    //	pause event
                    $cfs.bind(cf_e('pause', conf), function(e, imm, res) {
                        e.stopPropagation();
                        tmrs = sc_clearTimers(tmrs);

                        //	immediately pause
                        if (imm && crsl.isScrolling)
                        {
                            scrl.isStopped = true;
                            var nst = getTime() - scrl.startTime;
                            scrl.duration -= nst;
                            if (scrl.pre)
                            {
                                scrl.pre.duration -= nst;
                            }
                            if (scrl.post)
                            {
                                scrl.post.duration -= nst;
                            }
                            sc_stopScroll(scrl, false);
                        }

                        //	update remaining pause-time
                        if (!crsl.isPaused && !crsl.isScrolling)
                        {
                            if (res)
                            {
                                tmrs.timePassed += getTime() - tmrs.startTime;
                            }
                        }

                        //	button
                        if (!crsl.isPaused)
                        {
                            if (opts.auto.button)
                            {
                                opts.auto.button.addClass(cf_c('paused', conf));
                            }
                        }

                        //	set paused
                        crsl.isPaused = true;

                        //	pause pause callback
                        if (opts.auto.onTimeoutPause)
                        {
                            var dur1 = opts.auto.timeoutDuration - tmrs.timePassed,
                                perc = 100 - Math.ceil( dur1 * 100 / opts.auto.timeoutDuration );

                            opts.auto.onTimeoutPause.call($tt0, perc, dur1);
                        }
                        return true;
                    });


                    //	play event
                    $cfs.bind(cf_e('play', conf), function(e, dir, del, res) {
                        e.stopPropagation();
                        tmrs = sc_clearTimers(tmrs);

                        //	sort params
                        var v = [dir, del, res],
                            t = ['string', 'number', 'boolean'],
                            a = cf_sortParams(v, t);

                        dir = a[0];
                        del = a[1];
                        res = a[2];

                        if (dir != 'prev' && dir != 'next')
                        {
                            dir = crsl.direction;
                        }
                        if (!is_number(del))
                        {
                            del = 0;
                        }
                        if (!is_boolean(res))
                        {
                            res = false;
                        }

                        //	stopped?
                        if (res)
                        {
                            crsl.isStopped = false;
                            opts.auto.play = true;
                        }
                        if (!opts.auto.play)
                        {
                            e.stopImmediatePropagation();
                            return debug(conf, 'Carousel stopped: Not scrolling.');
                        }

                        //	button
                        if (crsl.isPaused)
                        {
                            if (opts.auto.button)
                            {
                                opts.auto.button.removeClass(cf_c('stopped', conf));
                                opts.auto.button.removeClass(cf_c('paused', conf));
                            }
                        }

                        //	set playing
                        crsl.isPaused = false;
                        tmrs.startTime = getTime();

                        //	timeout the scrolling
                        var dur1 = opts.auto.timeoutDuration + del;
                        dur2 = dur1 - tmrs.timePassed;
                        perc = 100 - Math.ceil(dur2 * 100 / dur1);

                        if (opts.auto.progress)
                        {
                            tmrs.progress = setInterval(function() {
                                var pasd = getTime() - tmrs.startTime + tmrs.timePassed,
                                    perc = Math.ceil(pasd * 100 / dur1);
                                opts.auto.progress.updater.call(opts.auto.progress.bar[0], perc);
                            }, opts.auto.progress.interval);
                        }

                        tmrs.auto = setTimeout(function() {
                            if (opts.auto.progress)
                            {
                                opts.auto.progress.updater.call(opts.auto.progress.bar[0], 100);
                            }
                            if (opts.auto.onTimeoutEnd)
                            {
                                opts.auto.onTimeoutEnd.call($tt0, perc, dur2);
                            }
                            if (crsl.isScrolling)
                            {
                                $cfs.trigger(cf_e('play', conf), dir);
                            }
                            else
                            {
                                $cfs.trigger(cf_e(dir, conf), opts.auto);
                            }
                        }, dur2);

                        //	pause start callback
                        if (opts.auto.onTimeoutStart)
                        {
                            opts.auto.onTimeoutStart.call($tt0, perc, dur2);
                        }

                        return true;
                    });


                    //	resume event
                    $cfs.bind(cf_e('resume', conf), function(e) {
                        e.stopPropagation();
                        if (scrl.isStopped)
                        {
                            scrl.isStopped = false;
                            crsl.isPaused = false;
                            crsl.isScrolling = true;
                            scrl.startTime = getTime();
                            sc_startScroll(scrl);
                        }
                        else
                        {
                            $cfs.trigger(cf_e('play', conf));
                        }
                        return true;
                    });


                    //	prev + next events
                    $cfs.bind(cf_e('prev', conf)+' '+cf_e('next', conf), function(e, obj, num, clb, que) {
                        e.stopPropagation();

                        //	stopped or hidden carousel, don't scroll, don't queue
                        if (crsl.isStopped || $cfs.is(':hidden'))
                        {
                            e.stopImmediatePropagation();
                            return debug(conf, 'Carousel stopped or hidden: Not scrolling.');
                        }

                        //	not enough items
                        var minimum = (is_number(opts.items.minimum)) ? opts.items.minimum : opts.items.visible + 1;
                        if (minimum > itms.total)
                        {
                            e.stopImmediatePropagation();
                            return debug(conf, 'Not enough items ('+itms.total+' total, '+minimum+' needed): Not scrolling.');
                        }

                        //	get config
                        var v = [obj, num, clb, que],
                            t = ['object', 'number/string', 'function', 'boolean'],
                            a = cf_sortParams(v, t);

                        obj = a[0];
                        num = a[1];
                        clb = a[2];
                        que = a[3];

                        var eType = e.type.slice(conf.events.prefix.length);

                        if (!is_object(obj))
                        {
                            obj = {};
                        }
                        if (is_function(clb))
                        {
                            obj.onAfter = clb;
                        }
                        if (is_boolean(que))
                        {
                            obj.queue = que;
                        }
                        obj = $.extend(true, {}, opts[eType], obj);

                        //	test conditions callback
                        if (obj.conditions && !obj.conditions.call($tt0, eType))
                        {
                            e.stopImmediatePropagation();
                            return debug(conf, 'Callback "conditions" returned false.');
                        }

                        if (!is_number(num))
                        {
                            if (opts.items.filter != '*')
                            {
                                num = 'visible';
                            }
                            else
                            {
                                var arr = [num, obj.items, opts[eType].items];
                                for (var a = 0, l = arr.length; a < l; a++)
                                {
                                    if (is_number(arr[a]) || arr[a] == 'page' || arr[a] == 'visible') {
                                        num = arr[a];
                                        break;
                                    }
                                }
                            }
                            switch(num) {
                                case 'page':
                                    e.stopImmediatePropagation();
                                    return $cfs.triggerHandler(cf_e(eType+'Page', conf), [obj, clb]);
                                    break;

                                case 'visible':
                                    if (!opts.items.visibleConf.variable && opts.items.filter == '*')
                                    {
                                        num = opts.items.visible;
                                    }
                                    break;
                            }
                        }

                        //	resume animation, add current to queue
                        if (scrl.isStopped)
                        {
                            $cfs.trigger(cf_e('resume', conf));
                            $cfs.trigger(cf_e('queue', conf), [eType, [obj, num, clb]]);
                            e.stopImmediatePropagation();
                            return debug(conf, 'Carousel resumed scrolling.');
                        }

                        //	queue if scrolling
                        if (obj.duration > 0)
                        {
                            if (crsl.isScrolling)
                            {
                                if (obj.queue)
                                {
                                    if (obj.queue == 'last')
                                    {
                                        queu = [];
                                    }
                                    if (obj.queue != 'first' || queu.length == 0)
                                    {
                                        $cfs.trigger(cf_e('queue', conf), [eType, [obj, num, clb]]);
                                    }
                                }
                                e.stopImmediatePropagation();
                                return debug(conf, 'Carousel currently scrolling.');
                            }
                        }

                        tmrs.timePassed = 0;
                        $cfs.trigger(cf_e('slide_'+eType, conf), [obj, num]);

                        //	synchronise
                        if (opts.synchronise)
                        {
                            var s = opts.synchronise,
                                c = [obj, num];

                            for (var j = 0, l = s.length; j < l; j++) {
                                var d = eType;
                                if (!s[j][2])
                                {
                                    d = (d == 'prev') ? 'next' : 'prev';
                                }
                                if (!s[j][1])
                                {
                                    c[0] = s[j][0].triggerHandler('_cfs_triggerEvent', ['configuration', d]);
                                }
                                c[1] = num + s[j][3];
                                s[j][0].trigger('_cfs_triggerEvent', ['slide_'+d, c]);
                            }
                        }
                        return true;
                    });


                    //	prev event
                    $cfs.bind(cf_e('slide_prev', conf), function(e, sO, nI) {
                        e.stopPropagation();
                        var a_itm = $cfs.children();

                        //	non-circular at start, scroll to end
                        if (!opts.circular)
                        {
                            if (itms.first == 0)
                            {
                                if (opts.infinite)
                                {
                                    $cfs.trigger(cf_e('next', conf), itms.total-1);
                                }
                                return e.stopImmediatePropagation();
                            }
                        }

                        sz_resetMargin(a_itm, opts);

                        //	find number of items to scroll
                        if (!is_number(nI))
                        {
                            if (opts.items.visibleConf.variable)
                            {
                                nI = gn_getVisibleItemsPrev(a_itm, opts, itms.total-1);
                            }
                            else if (opts.items.filter != '*')
                            {
                                var xI = (is_number(sO.items)) ? sO.items : gn_getVisibleOrg($cfs, opts);
                                nI = gn_getScrollItemsPrevFilter(a_itm, opts, itms.total-1, xI);
                            }
                            else
                            {
                                nI = opts.items.visible;
                            }
                            nI = cf_getAdjust(nI, opts, sO.items, $tt0);
                        }

                        //	prevent non-circular from scrolling to far
                        if (!opts.circular)
                        {
                            if (itms.total - nI < itms.first)
                            {
                                nI = itms.total - itms.first;
                            }
                        }

                        //	set new number of visible items
                        opts.items.visibleConf.old = opts.items.visible;
                        if (opts.items.visibleConf.variable)
                        {
                            var vI = cf_getItemsAdjust(gn_getVisibleItemsNext(a_itm, opts, itms.total-nI), opts, opts.items.visibleConf.adjust, $tt0);
                            if (opts.items.visible+nI <= vI && nI < itms.total)
                            {
                                nI++;
                                vI = cf_getItemsAdjust(gn_getVisibleItemsNext(a_itm, opts, itms.total-nI), opts, opts.items.visibleConf.adjust, $tt0);
                            }
                            opts.items.visible = vI;
                        }
                        else if (opts.items.filter != '*')
                        {
                            var vI = gn_getVisibleItemsNextFilter(a_itm, opts, itms.total-nI);
                            opts.items.visible = cf_getItemsAdjust(vI, opts, opts.items.visibleConf.adjust, $tt0);
                        }

                        sz_resetMargin(a_itm, opts, true);

                        //	scroll 0, don't scroll
                        if (nI == 0)
                        {
                            e.stopImmediatePropagation();
                            return debug(conf, '0 items to scroll: Not scrolling.');
                        }
                        debug(conf, 'Scrolling '+nI+' items backward.');


                        //	save new config
                        itms.first += nI;
                        while (itms.first >= itms.total)
                        {
                            itms.first -= itms.total;
                        }

                        //	non-circular callback
                        if (!opts.circular)
                        {
                            if (itms.first == 0 && sO.onEnd)
                            {
                                sO.onEnd.call($tt0, 'prev');
                            }
                            if (!opts.infinite)
                            {
                                nv_enableNavi(opts, itms.first, conf);
                            }
                        }

                        //	rearrange items
                        $cfs.children().slice(itms.total-nI, itms.total).prependTo($cfs);
                        if (itms.total < opts.items.visible + nI)
                        {
                            $cfs.children().slice(0, (opts.items.visible+nI)-itms.total).clone(true).appendTo($cfs);
                        }

                        //	the needed items
                        var a_itm = $cfs.children(),
                            i_old = gi_getOldItemsPrev(a_itm, opts, nI),
                            i_new = gi_getNewItemsPrev(a_itm, opts),
                            i_cur_l = a_itm.eq(nI-1),
                            i_old_l = i_old.last(),
                            i_new_l = i_new.last();

                        sz_resetMargin(a_itm, opts);

                        var pL = 0,
                            pR = 0;

                        if (opts.align)
                        {
                            var p = cf_getAlignPadding(i_new, opts);
                            pL = p[0];
                            pR = p[1];
                        }
                        var oL = (pL < 0) ? opts.padding[opts.d[3]] : 0;

                        //	hide items for fx directscroll
                        var hiddenitems = false,
                            i_skp = $();
                        if (opts.items.visible < nI)
                        {
                            i_skp = a_itm.slice(opts.items.visibleConf.old, nI);
                            if (sO.fx == 'directscroll')
                            {
                                var orgW = opts.items[opts.d['width']];
                                hiddenitems = i_skp;
                                i_cur_l = i_new_l;
                                sc_hideHiddenItems(hiddenitems);
                                opts.items[opts.d['width']] = 'variable';
                            }
                        }

                        //	save new sizes
                        var $cf2 = false,
                            i_siz = ms_getTotalSize(a_itm.slice(0, nI), opts, 'width'),
                            w_siz = cf_mapWrapperSizes(ms_getSizes(i_new, opts, true), opts, !opts.usePadding),
                            i_siz_vis = 0,
                            a_cfs = {},
                            a_wsz = {},
                            a_cur = {},
                            a_old = {},
                            a_new = {},
                            a_lef = {},
                            a_lef_vis = {},
                            a_dur = sc_getDuration(sO, opts, nI, i_siz);

                        switch(sO.fx)
                        {
                            case 'cover':
                            case 'cover-fade':
                                i_siz_vis = ms_getTotalSize(a_itm.slice(0, opts.items.visible), opts, 'width');
                                break;
                        }

                        if (hiddenitems)
                        {
                            opts.items[opts.d['width']] = orgW;
                        }

                        sz_resetMargin(a_itm, opts, true);
                        if (pR >= 0)
                        {
                            sz_resetMargin(i_old_l, opts, opts.padding[opts.d[1]]);
                        }
                        if (pL >= 0)
                        {
                            sz_resetMargin(i_cur_l, opts, opts.padding[opts.d[3]]);
                        }

                        if (opts.align)
                        {
                            opts.padding[opts.d[1]] = pR;
                            opts.padding[opts.d[3]] = pL;
                        }

                        a_lef[opts.d['left']] = -(i_siz - oL);
                        a_lef_vis[opts.d['left']] = -(i_siz_vis - oL);
                        a_wsz[opts.d['left']] = w_siz[opts.d['width']];

                        //	scrolling functions
                        var _s_wrapper = function() {},
                            _a_wrapper = function() {},
                            _s_paddingold = function() {},
                            _a_paddingold = function() {},
                            _s_paddingnew = function() {},
                            _a_paddingnew = function() {},
                            _s_paddingcur = function() {},
                            _a_paddingcur = function() {},
                            _onafter = function() {},
                            _moveitems = function() {},
                            _position = function() {};

                        //	clone carousel
                        switch(sO.fx)
                        {
                            case 'crossfade':
                            case 'cover':
                            case 'cover-fade':
                            case 'uncover':
                            case 'uncover-fade':
                                $cf2 = $cfs.clone(true).appendTo($wrp);
                                break;
                        }
                        switch(sO.fx)
                        {
                            case 'crossfade':
                            case 'uncover':
                            case 'uncover-fade':
                                $cf2.children().slice(0, nI).remove();
                                $cf2.children().slice(opts.items.visibleConf.old).remove();
                                break;

                            case 'cover':
                            case 'cover-fade':
                                $cf2.children().slice(opts.items.visible).remove();
                                $cf2.css(a_lef_vis);
                                break;
                        }

                        $cfs.css(a_lef);

                        //	reset all scrolls
                        scrl = sc_setScroll(a_dur, sO.easing);

                        //	animate / set carousel
                        a_cfs[opts.d['left']] = (opts.usePadding) ? opts.padding[opts.d[3]] : 0;

                        //	animate / set wrapper
                        if (opts[opts.d['width']] == 'variable' || opts[opts.d['height']] == 'variable')
                        {
                            _s_wrapper = function() {
                                $wrp.css(w_siz);
                            };
                            _a_wrapper = function() {
                                scrl.anims.push([$wrp, w_siz]);
                            };
                        }

                        //	animate / set items
                        if (opts.usePadding)
                        {
                            if (i_new_l.not(i_cur_l).length)
                            {
                                a_cur[opts.d['marginRight']] = i_cur_l.data('_cfs_origCssMargin');

                                if (pL < 0)
                                {
                                    i_cur_l.css(a_cur);
                                }
                                else
                                {
                                    _s_paddingcur = function() {
                                        i_cur_l.css(a_cur);
                                    };
                                    _a_paddingcur = function() {
                                        scrl.anims.push([i_cur_l, a_cur]);
                                    };
                                }
                            }
                            switch(sO.fx)
                            {
                                case 'cover':
                                case 'cover-fade':
                                    $cf2.children().eq(nI-1).css(a_cur);
                                    break;
                            }

                            if (i_new_l.not(i_old_l).length)
                            {
                                a_old[opts.d['marginRight']] = i_old_l.data('_cfs_origCssMargin');
                                _s_paddingold = function() {
                                    i_old_l.css(a_old);
                                };
                                _a_paddingold = function() {
                                    scrl.anims.push([i_old_l, a_old]);
                                };
                            }

                            if (pR >= 0)
                            {
                                a_new[opts.d['marginRight']] = i_new_l.data('_cfs_origCssMargin') + opts.padding[opts.d[1]];
                                _s_paddingnew = function() {
                                    i_new_l.css(a_new);
                                };
                                _a_paddingnew = function() {
                                    scrl.anims.push([i_new_l, a_new]);
                                };
                            }
                        }

                        //	set position
                        _position = function() {
                            $cfs.css(a_cfs);
                        };


                        var overFill = opts.items.visible+nI-itms.total;

                        //	rearrange items
                        _moveitems = function() {
                            if (overFill > 0)
                            {
                                $cfs.children().slice(itms.total).remove();
                                i_old = $( $cfs.children().slice(itms.total-(opts.items.visible-overFill)).get().concat( $cfs.children().slice(0, overFill).get() ) );
                            }
                            sc_showHiddenItems(hiddenitems);

                            if (opts.usePadding)
                            {
                                var l_itm = $cfs.children().eq(opts.items.visible+nI-1);
                                l_itm.css(opts.d['marginRight'], l_itm.data('_cfs_origCssMargin'));
                            }
                        };


                        var cb_arguments = sc_mapCallbackArguments(i_old, i_skp, i_new, nI, 'prev', a_dur, w_siz);

                        //	fire onAfter callbacks
                        _onafter = function() {
                            sc_afterScroll($cfs, $cf2, sO);
                            crsl.isScrolling = false;
                            clbk.onAfter = sc_fireCallbacks($tt0, sO, 'onAfter', cb_arguments, clbk);
                            queu = sc_fireQueue($cfs, queu, conf);

                            if (!crsl.isPaused)
                            {
                                $cfs.trigger(cf_e('play', conf));
                            }
                        };

                        //	fire onBefore callback
                        crsl.isScrolling = true;
                        tmrs = sc_clearTimers(tmrs);
                        clbk.onBefore = sc_fireCallbacks($tt0, sO, 'onBefore', cb_arguments, clbk);

                        switch(sO.fx)
                        {
                            case 'none':
                                $cfs.css(a_cfs);
                                _s_wrapper();
                                _s_paddingold();
                                _s_paddingnew();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                _onafter();
                                break;

                            case 'fade':
                                scrl.anims.push([$cfs, { 'opacity': 0 }, function() {
                                    _s_wrapper();
                                    _s_paddingold();
                                    _s_paddingnew();
                                    _s_paddingcur();
                                    _position();
                                    _moveitems();
                                    scrl = sc_setScroll(a_dur, sO.easing);
                                    scrl.anims.push([$cfs, { 'opacity': 1 }, _onafter]);
                                    sc_startScroll(scrl);
                                }]);
                                break;

                            case 'crossfade':
                                $cfs.css({ 'opacity': 0 });
                                scrl.anims.push([$cf2, { 'opacity': 0 }]);
                                scrl.anims.push([$cfs, { 'opacity': 1 }, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingnew();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                break;

                            case 'cover':
                                scrl.anims.push([$cf2, a_cfs, function() {
                                    _s_paddingold();
                                    _s_paddingnew();
                                    _s_paddingcur();
                                    _position();
                                    _moveitems();
                                    _onafter();
                                }]);
                                _a_wrapper();
                                break;

                            case 'cover-fade':
                                scrl.anims.push([$cfs, { 'opacity': 0 }]);
                                scrl.anims.push([$cf2, a_cfs, function() {
                                    $cfs.css({ 'opacity': 1 });
                                    _s_paddingold();
                                    _s_paddingnew();
                                    _s_paddingcur();
                                    _position();
                                    _moveitems();
                                    _onafter();
                                }]);
                                _a_wrapper();
                                break;

                            case 'uncover':
                                scrl.anims.push([$cf2, a_wsz, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingnew();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                break;

                            case 'uncover-fade':
                                $cfs.css({ 'opacity': 0 });
                                scrl.anims.push([$cfs, { 'opacity': 1 }]);
                                scrl.anims.push([$cf2, a_wsz, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingnew();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                break;

                            default:
                                scrl.anims.push([$cfs, a_cfs, function() {
                                    _moveitems();
                                    _onafter();
                                }]);
                                _a_wrapper();
                                _a_paddingold();
                                _a_paddingnew();
                                _a_paddingcur();
                                break;
                        }

                        sc_startScroll(scrl);
                        cf_setCookie(opts.cookie, $cfs, conf);

                        $cfs.trigger(cf_e('updatePageStatus', conf), [false, w_siz]);

                        return true;
                    });


                    //	next event
                    $cfs.bind(cf_e('slide_next', conf), function(e, sO, nI) {
                        e.stopPropagation();
                        var a_itm = $cfs.children();

                        //	non-circular at end, scroll to start
                        if (!opts.circular)
                        {
                            if (itms.first == opts.items.visible)
                            {
                                if (opts.infinite)
                                {
                                    $cfs.trigger(cf_e('prev', conf), itms.total-1);
                                }
                                return e.stopImmediatePropagation();
                            }
                        }

                        sz_resetMargin(a_itm, opts);

                        //	find number of items to scroll
                        if (!is_number(nI))
                        {
                            if (opts.items.filter != '*')
                            {
                                var xI = (is_number(sO.items)) ? sO.items : gn_getVisibleOrg($cfs, opts);
                                nI = gn_getScrollItemsNextFilter(a_itm, opts, 0, xI);
                            }
                            else
                            {
                                nI = opts.items.visible;
                            }
                            nI = cf_getAdjust(nI, opts, sO.items, $tt0);
                        }

                        var lastItemNr = (itms.first == 0) ? itms.total : itms.first;

                        //	prevent non-circular from scrolling to far
                        if (!opts.circular)
                        {
                            if (opts.items.visibleConf.variable)
                            {
                                var vI = gn_getVisibleItemsNext(a_itm, opts, nI),
                                    xI = gn_getVisibleItemsPrev(a_itm, opts, lastItemNr-1);
                            }
                            else
                            {
                                var vI = opts.items.visible,
                                    xI = opts.items.visible;
                            }

                            if (nI + vI > lastItemNr)
                            {
                                nI = lastItemNr - xI;
                            }
                        }

                        //	set new number of visible items
                        opts.items.visibleConf.old = opts.items.visible;
                        if (opts.items.visibleConf.variable)
                        {
                            var vI = cf_getItemsAdjust(gn_getVisibleItemsNextTestCircular(a_itm, opts, nI, lastItemNr), opts, opts.items.visibleConf.adjust, $tt0);
                            while (opts.items.visible-nI >= vI && nI < itms.total)
                            {
                                nI++;
                                vI = cf_getItemsAdjust(gn_getVisibleItemsNextTestCircular(a_itm, opts, nI, lastItemNr), opts, opts.items.visibleConf.adjust, $tt0);
                            }
                            opts.items.visible = vI;
                        }
                        else if (opts.items.filter != '*')
                        {
                            var vI = gn_getVisibleItemsNextFilter(a_itm, opts, nI);
                            opts.items.visible = cf_getItemsAdjust(vI, opts, opts.items.visibleConf.adjust, $tt0);
                        }

                        sz_resetMargin(a_itm, opts, true);

                        //	scroll 0, don't scroll
                        if (nI == 0)
                        {
                            e.stopImmediatePropagation();
                            return debug(conf, '0 items to scroll: Not scrolling.');
                        }
                        debug(conf, 'Scrolling '+nI+' items forward.');


                        //	save new config
                        itms.first -= nI;
                        while (itms.first < 0)
                        {
                            itms.first += itms.total;
                        }

                        //	non-circular callback
                        if (!opts.circular)
                        {
                            if (itms.first == opts.items.visible && sO.onEnd)
                            {
                                sO.onEnd.call($tt0, 'next');
                            }
                            if (!opts.infinite)
                            {
                                nv_enableNavi(opts, itms.first, conf);
                            }
                        }

                        //	rearrange items
                        if (itms.total < opts.items.visible+nI)
                        {
                            $cfs.children().slice(0, (opts.items.visible+nI)-itms.total).clone(true).appendTo($cfs);
                        }

                        //	the needed items
                        var a_itm = $cfs.children(),
                            i_old = gi_getOldItemsNext(a_itm, opts),
                            i_new = gi_getNewItemsNext(a_itm, opts, nI),
                            i_cur_l = a_itm.eq(nI-1),
                            i_old_l = i_old.last(),
                            i_new_l = i_new.last();

                        sz_resetMargin(a_itm, opts);

                        var pL = 0,
                            pR = 0;

                        if (opts.align)
                        {
                            var p = cf_getAlignPadding(i_new, opts);
                            pL = p[0];
                            pR = p[1];
                        }

                        //	hide items for fx directscroll
                        var hiddenitems = false,
                            i_skp = $();
                        if (opts.items.visibleConf.old < nI)
                        {
                            i_skp = a_itm.slice(opts.items.visibleConf.old, nI);
                            if (sO.fx == 'directscroll')
                            {
                                var orgW = opts.items[opts.d['width']];
                                hiddenitems = i_skp;
                                i_cur_l = i_old_l;
                                sc_hideHiddenItems(hiddenitems);
                                opts.items[opts.d['width']] = 'variable';
                            }
                        }

                        //	save new sizes
                        var $cf2 = false,
                            i_siz = ms_getTotalSize(a_itm.slice(0, nI), opts, 'width'),
                            w_siz = cf_mapWrapperSizes(ms_getSizes(i_new, opts, true), opts, !opts.usePadding),
                            i_siz_vis = 0,
                            a_cfs = {},
                            a_cfs_vis = {},
                            a_cur = {},
                            a_old = {},
                            a_lef = {},
                            a_dur = sc_getDuration(sO, opts, nI, i_siz);

                        switch(sO.fx)
                        {
                            case 'uncover':
                            case 'uncover-fade':
                                i_siz_vis = ms_getTotalSize(a_itm.slice(0, opts.items.visibleConf.old), opts, 'width');
                                break;
                        }

                        if (hiddenitems)
                        {
                            opts.items[opts.d['width']] = orgW;
                        }

                        if (opts.align)
                        {
                            if (opts.padding[opts.d[1]] < 0)
                            {
                                opts.padding[opts.d[1]] = 0;
                            }
                        }
                        sz_resetMargin(a_itm, opts, true);
                        sz_resetMargin(i_old_l, opts, opts.padding[opts.d[1]]);

                        if (opts.align)
                        {
                            opts.padding[opts.d[1]] = pR;
                            opts.padding[opts.d[3]] = pL;
                        }

                        a_lef[opts.d['left']] = (opts.usePadding) ? opts.padding[opts.d[3]] : 0;

                        //	scrolling functions
                        var _s_wrapper = function() {},
                            _a_wrapper = function() {},
                            _s_paddingold = function() {},
                            _a_paddingold = function() {},
                            _s_paddingcur = function() {},
                            _a_paddingcur = function() {},
                            _onafter = function() {},
                            _moveitems = function() {},
                            _position = function() {};

                        //	clone carousel
                        switch(sO.fx)
                        {
                            case 'crossfade':
                            case 'cover':
                            case 'cover-fade':
                            case 'uncover':
                            case 'uncover-fade':
                                $cf2 = $cfs.clone(true).appendTo($wrp);
                                $cf2.children().slice(opts.items.visibleConf.old).remove();
                                break;
                        }
                        switch(sO.fx)
                        {
                            case 'crossfade':
                            case 'cover':
                            case 'cover-fade':
                                $cfs.css('zIndex', 1);
                                $cf2.css('zIndex', 0);
                                break;
                        }

                        //	reset all scrolls
                        scrl = sc_setScroll(a_dur, sO.easing);

                        //	animate / set carousel
                        a_cfs[opts.d['left']] = -i_siz;
                        a_cfs_vis[opts.d['left']] = -i_siz_vis;

                        if (pL < 0)
                        {
                            a_cfs[opts.d['left']] += pL;
                        }

                        //	animate / set wrapper
                        if (opts[opts.d['width']] == 'variable' || opts[opts.d['height']] == 'variable')
                        {
                            _s_wrapper = function() {
                                $wrp.css(w_siz);
                            };
                            _a_wrapper = function() {
                                scrl.anims.push([$wrp, w_siz]);
                            };
                        }

                        //	animate / set items
                        if (opts.usePadding)
                        {
                            var i_new_l_m = i_new_l.data('_cfs_origCssMargin');
                            if (pR >= 0)
                            {
                                i_new_l_m += opts.padding[opts.d[1]];
                            }
                            i_new_l.css(opts.d['marginRight'], i_new_l_m);

                            if (i_cur_l.not(i_old_l).length)
                            {
                                a_old[opts.d['marginRight']] = i_old_l.data('_cfs_origCssMargin');
                            }
                            _s_paddingold = function() {
                                i_old_l.css(a_old);
                            };
                            _a_paddingold = function() {
                                scrl.anims.push([i_old_l, a_old]);
                            };

                            var i_cur_l_m = i_cur_l.data('_cfs_origCssMargin');
                            if (pL > 0)
                            {
                                i_cur_l_m += opts.padding[opts.d[3]];
                            }
                            a_cur[opts.d['marginRight']] = i_cur_l_m;
                            _s_paddingcur = function() {
                                i_cur_l.css(a_cur);
                            };
                            _a_paddingcur = function() {
                                scrl.anims.push([i_cur_l, a_cur]);
                            };
                        }

                        //	set position
                        _position = function() {
                            $cfs.css(a_lef);
                        };


                        var overFill = opts.items.visible+nI-itms.total;

                        //	rearrange items
                        _moveitems = function() {
                            if (overFill > 0)
                            {
                                $cfs.children().slice(itms.total).remove();
                            }
                            var l_itm = $cfs.children().slice(0, nI).appendTo($cfs).last();
                            if (overFill > 0)
                            {
                                i_new = gi_getCurrentItems(a_itm, opts);
                            }
                            sc_showHiddenItems(hiddenitems);

                            if (opts.usePadding)
                            {
                                if (itms.total < opts.items.visible+nI) {
                                    var i_cur_l = $cfs.children().eq(opts.items.visible-1);
                                    i_cur_l.css(opts.d['marginRight'], i_cur_l.data('_cfs_origCssMargin') + opts.padding[opts.d[3]]);
                                }
                                l_itm.css(opts.d['marginRight'], l_itm.data('_cfs_origCssMargin'));
                            }
                        };


                        var cb_arguments = sc_mapCallbackArguments(i_old, i_skp, i_new, nI, 'next', a_dur, w_siz);

                        //	fire onAfter callbacks
                        _onafter = function() {
                            $cfs.css('zIndex', $cfs.data('_cfs_origCss').zIndex);
                            sc_afterScroll($cfs, $cf2, sO);
                            crsl.isScrolling = false;
                            clbk.onAfter = sc_fireCallbacks($tt0, sO, 'onAfter', cb_arguments, clbk);
                            queu = sc_fireQueue($cfs, queu, conf);

                            if (!crsl.isPaused)
                            {
                                $cfs.trigger(cf_e('play', conf));
                            }
                        };

                        //	fire onBefore callbacks
                        crsl.isScrolling = true;
                        tmrs = sc_clearTimers(tmrs);
                        clbk.onBefore = sc_fireCallbacks($tt0, sO, 'onBefore', cb_arguments, clbk);

                        switch(sO.fx)
                        {
                            case 'none':
                                $cfs.css(a_cfs);
                                _s_wrapper();
                                _s_paddingold();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                _onafter();
                                break;

                            case 'fade':
                                scrl.anims.push([$cfs, { 'opacity': 0 }, function() {
                                    _s_wrapper();
                                    _s_paddingold();
                                    _s_paddingcur();
                                    _position();
                                    _moveitems();
                                    scrl = sc_setScroll(a_dur, sO.easing);
                                    scrl.anims.push([$cfs, { 'opacity': 1 }, _onafter]);
                                    sc_startScroll(scrl);
                                }]);
                                break;

                            case 'crossfade':
                                $cfs.css({ 'opacity': 0 });
                                scrl.anims.push([$cf2, { 'opacity': 0 }]);
                                scrl.anims.push([$cfs, { 'opacity': 1 }, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                break;

                            case 'cover':
                                $cfs.css(opts.d['left'], $wrp[opts.d['width']]());
                                scrl.anims.push([$cfs, a_lef, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingcur();
                                _moveitems();
                                break;

                            case 'cover-fade':
                                $cfs.css(opts.d['left'], $wrp[opts.d['width']]());
                                scrl.anims.push([$cf2, { 'opacity': 0 }]);
                                scrl.anims.push([$cfs, a_lef, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingcur();
                                _moveitems();
                                break;

                            case 'uncover':
                                scrl.anims.push([$cf2, a_cfs_vis, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                break;

                            case 'uncover-fade':
                                $cfs.css({ 'opacity': 0 });
                                scrl.anims.push([$cfs, { 'opacity': 1 }]);
                                scrl.anims.push([$cf2, a_cfs_vis, _onafter]);
                                _a_wrapper();
                                _s_paddingold();
                                _s_paddingcur();
                                _position();
                                _moveitems();
                                break;

                            default:
                                scrl.anims.push([$cfs, a_cfs, function() {
                                    _position();
                                    _moveitems();
                                    _onafter();
                                }]);
                                _a_wrapper();
                                _a_paddingold();
                                _a_paddingcur();
                                break;
                        }

                        sc_startScroll(scrl);
                        cf_setCookie(opts.cookie, $cfs, conf);

                        $cfs.trigger(cf_e('updatePageStatus', conf), [false, w_siz]);

                        return true;
                    });


                    //	slideTo event
                    $cfs.bind(cf_e('slideTo', conf), function(e, num, dev, org, obj, dir, clb) {
                        e.stopPropagation();

                        var v = [num, dev, org, obj, dir, clb],
                            t = ['string/number/object', 'number', 'boolean', 'object', 'string', 'function'],
                            a = cf_sortParams(v, t);

                        obj = a[3];
                        dir = a[4];
                        clb = a[5];

                        num = gn_getItemIndex(a[0], a[1], a[2], itms, $cfs);

                        if (num == 0)
                        {
                            return false;
                        }
                        if (!is_object(obj))
                        {
                            obj = false;
                        }

                        /*
                         if (crsl.isScrolling)
                         {
                         if (!is_object(obj) || obj.duration > 0)
                         {
                         //						return false;
                         }
                         }
                         */

                        if (dir != 'prev' && dir != 'next')
                        {
                            if (opts.circular)
                            {
                                dir = (num <= itms.total / 2) ? 'next' : 'prev';
                            }
                            else
                            {
                                dir = (itms.first == 0 || itms.first > num) ? 'next' : 'prev';
                            }
                        }

                        if (dir == 'prev')
                        {
                            num = itms.total-num;
                        }
                        $cfs.trigger(cf_e(dir, conf), [obj, num, clb]);

                        return true;
                    });


                    //	prevPage event
                    $cfs.bind(cf_e('prevPage', conf), function(e, obj, clb) {
                        e.stopPropagation();
                        var cur = $cfs.triggerHandler(cf_e('currentPage', conf));
                        return $cfs.triggerHandler(cf_e('slideToPage', conf), [cur-1, obj, 'prev', clb]);
                    });


                    //	nextPage event
                    $cfs.bind(cf_e('nextPage', conf), function(e, obj, clb) {
                        e.stopPropagation();
                        var cur = $cfs.triggerHandler(cf_e('currentPage', conf));
                        return $cfs.triggerHandler(cf_e('slideToPage', conf), [cur+1, obj, 'next', clb]);
                    });


                    //	slideToPage event
                    $cfs.bind(cf_e('slideToPage', conf), function(e, pag, obj, dir, clb) {
                        e.stopPropagation();
                        if (!is_number(pag))
                        {
                            pag = $cfs.triggerHandler(cf_e('currentPage', conf));
                        }
                        var ipp = opts.pagination.items || opts.items.visible,
                            max = Math.ceil(itms.total / ipp)-1;

                        if (pag < 0)
                        {
                            pag = max;
                        }
                        if (pag > max)
                        {
                            pag = 0;
                        }
                        return $cfs.triggerHandler(cf_e('slideTo', conf), [pag*ipp, 0, true, obj, dir, clb]);
                    });

                    //	jumpToStart event
                    $cfs.bind(cf_e('jumpToStart', conf), function(e, s) {
                        e.stopPropagation();
                        if (s)
                        {
                            s = gn_getItemIndex(s, 0, true, itms, $cfs);
                        }
                        else
                        {
                            s = 0;
                        }

                        s += itms.first;
                        if (s != 0)
                        {
                            if (itms.total > 0)
                            {
                                while (s > itms.total)
                                {
                                    s -= itms.total;
                                }
                            }
                            $cfs.prepend($cfs.children().slice(s, itms.total));
                        }
                        return true;
                    });


                    //	synchronise event
                    $cfs.bind(cf_e('synchronise', conf), function(e, s) {
                        e.stopPropagation();
                        if (s)
                        {
                            s = cf_getSynchArr(s);
                        }
                        else if (opts.synchronise)
                        {
                            s = opts.synchronise;
                        }
                        else
                        {
                            return debug(conf, 'No carousel to synchronise.');
                        }

                        var n = $cfs.triggerHandler(cf_e('currentPosition', conf)),
                            x = true;

                        for (var j = 0, l = s.length; j < l; j++)
                        {
                            if (!s[j][0].triggerHandler(cf_e('slideTo', conf), [n, s[j][3], true]))
                            {
                                x = false;
                            }
                        }
                        return x;
                    });


                    //	queue event
                    $cfs.bind(cf_e('queue', conf), function(e, dir, opt) {
                        e.stopPropagation();
                        if (is_function(dir))
                        {
                            dir.call($tt0, queu);
                        }
                        else if (is_array(dir))
                        {
                            queu = dir;
                        }
                        else if (!is_undefined(dir))
                        {
                            queu.push([dir, opt]);
                        }
                        return queu;
                    });


                    //	insertItem event
                    $cfs.bind(cf_e('insertItem', conf), function(e, itm, num, org, dev) {
                        e.stopPropagation();

                        var v = [itm, num, org, dev],
                            t = ['string/object', 'string/number/object', 'boolean', 'number'],
                            a = cf_sortParams(v, t);

                        itm = a[0];
                        num = a[1];
                        org = a[2];
                        dev = a[3];

                        if (is_object(itm) && !is_jquery(itm))
                        {
                            itm = $(itm);
                        }
                        else if (is_string(itm))
                        {
                            itm = $(itm);
                        }
                        if (!is_jquery(itm) || itm.length == 0)
                        {
                            return debug(conf, 'Not a valid object.');
                        }

                        if (is_undefined(num))
                        {
                            num = 'end';
                        }

                        sz_storeMargin(itm, opts);
                        sz_storeSizes(itm, opts);

                        var orgNum = num,
                            before = 'before';

                        if (num == 'end')
                        {
                            if (org)
                            {
                                if (itms.first == 0)
                                {
                                    num = itms.total-1;
                                    before = 'after';
                                }
                                else
                                {
                                    num = itms.first;
                                    itms.first += itm.length;
                                }
                                if (num < 0)
                                {
                                    num = 0;
                                }
                            }
                            else
                            {
                                num = itms.total-1;
                                before = 'after';
                            }
                        }
                        else
                        {
                            num = gn_getItemIndex(num, dev, org, itms, $cfs);
                        }

                        var $cit = $cfs.children().eq(num);
                        if ($cit.length)
                        {
                            $cit[before](itm);
                        }
                        else
                        {
                            debug(conf, 'Correct insert-position not found! Appending item to the end.');
                            $cfs.append(itm);
                        }

                        if (orgNum != 'end' && !org)
                        {
                            if (num < itms.first)
                            {
                                itms.first += itm.length;
                            }
                        }
                        itms.total = $cfs.children().length;
                        if (itms.first >= itms.total)
                        {
                            itms.first -= itms.total;
                        }

                        $cfs.trigger(cf_e('updateSizes', conf));
                        $cfs.trigger(cf_e('linkAnchors', conf));

                        return true;
                    });


                    //	removeItem event
                    $cfs.bind(cf_e('removeItem', conf), function(e, num, org, dev) {
                        e.stopPropagation();

                        var v = [num, org, dev],
                            t = ['string/number/object', 'boolean', 'number'],
                            a = cf_sortParams(v, t);

                        num = a[0];
                        org = a[1];
                        dev = a[2];

                        var removed = false;
                        if (num instanceof $ && num.length > 1)
                        {
                            $removed = $();
                            num.each(function(i, el) {
                                var $rem = $cfs.trigger(cf_e('removeItem', conf), [$(this), org, dev]);
                                if ($rem) $removed = $removed.add($rem);
                            });
                            return $removed;
                        }

                        if (is_undefined(num) || num == 'end')
                        {
                            $removed = $cfs.children().last();
                        }
                        else
                        {
                            num = gn_getItemIndex(num, dev, org, itms, $cfs);
                            var $removed = $cfs.children().eq(num);
                            if ($removed.length){
                                if (num < itms.first) itms.first -= $removed.length;
                            }
                        }
                        if ($removed && $removed.length)
                        {
                            $removed.detach();
                            itms.total = $cfs.children().length;
                            $cfs.trigger(cf_e('updateSizes', conf));
                        }

                        return $removed;
                    });


                    //	onBefore and onAfter event
                    $cfs.bind(cf_e('onBefore', conf)+' '+cf_e('onAfter', conf), function(e, fn) {
                        e.stopPropagation();
                        var eType = e.type.slice(conf.events.prefix.length);
                        if (is_array(fn))
                        {
                            clbk[eType] = fn;
                        }
                        if (is_function(fn))
                        {
                            clbk[eType].push(fn);
                        }
                        return clbk[eType];
                    });


                    //	currentPosition event
                    $cfs.bind(cf_e('currentPosition', conf), function(e, fn) {
                        e.stopPropagation();
                        if (itms.first == 0)
                        {
                            var val = 0;
                        }
                        else
                        {
                            var val = itms.total - itms.first;
                        }
                        if (is_function(fn))
                        {
                            fn.call($tt0, val);
                        }
                        return val;
                    });


                    //	currentPage event
                    $cfs.bind(cf_e('currentPage', conf), function(e, fn) {
                        e.stopPropagation();
                        var ipp = opts.pagination.items || opts.items.visible,
                            max = Math.ceil(itms.total/ipp-1),
                            nr;
                        if (itms.first == 0)
                        {
                            nr = 0;
                        }
                        else if (itms.first < itms.total % ipp)
                        {
                            nr = 0;
                        }
                        else if (itms.first == ipp && !opts.circular)
                        {
                            nr = max;
                        }
                        else
                        {
                            nr = Math.round((itms.total-itms.first)/ipp);
                        }
                        if (nr < 0)
                        {
                            nr = 0;
                        }
                        if (nr > max)
                        {
                            nr = max;
                        }
                        if (is_function(fn))
                        {
                            fn.call($tt0, nr);
                        }
                        return nr;
                    });


                    //	currentVisible event
                    $cfs.bind(cf_e('currentVisible', conf), function(e, fn) {
                        e.stopPropagation();
                        var $i = gi_getCurrentItems($cfs.children(), opts);
                        if (is_function(fn))
                        {
                            fn.call($tt0, $i);
                        }
                        return $i;
                    });


                    //	slice event
                    $cfs.bind(cf_e('slice', conf), function(e, f, l, fn) {
                        e.stopPropagation();

                        if (itms.total == 0)
                        {
                            return false;
                        }

                        var v = [f, l, fn],
                            t = ['number', 'number', 'function'],
                            a = cf_sortParams(v, t);

                        f = (is_number(a[0])) ? a[0] : 0;
                        l = (is_number(a[1])) ? a[1] : itms.total;
                        fn = a[2];

                        f += itms.first;
                        l += itms.first;

                        if (items.total > 0)
                        {
                            while (f > itms.total)
                            {
                                f -= itms.total;
                            }
                            while (l > itms.total)
                            {
                                l -= itms.total;
                            }
                            while (f < 0)
                            {
                                f += itms.total;
                            }
                            while (l < 0)
                            {
                                l += itms.total;
                            }
                        }
                        var $iA = $cfs.children(),
                            $i;

                        if (l > f)
                        {
                            $i = $iA.slice(f, l);
                        }
                        else
                        {
                            $i = $( $iA.slice(f, itms.total).get().concat( $iA.slice(0, l).get() ) );
                        }

                        if (is_function(fn))
                        {
                            fn.call($tt0, $i);
                        }
                        return $i;
                    });


                    //	isPaused, isStopped and isScrolling events
                    $cfs.bind(cf_e('isPaused', conf)+' '+cf_e('isStopped', conf)+' '+cf_e('isScrolling', conf), function(e, fn) {
                        e.stopPropagation();
                        var eType = e.type.slice(conf.events.prefix.length),
                            value = crsl[eType];
                        if (is_function(fn))
                        {
                            fn.call($tt0, value);
                        }
                        return value;
                    });


                    //	configuration event
                    $cfs.bind(cf_e('configuration', conf), function(e, a, b, c) {
                        e.stopPropagation();
                        var reInit = false;

                        //	return entire configuration-object
                        if (is_function(a))
                        {
                            a.call($tt0, opts);
                        }
                        //	set multiple options via object
                        else if (is_object(a))
                        {
                            opts_orig = $.extend(true, {}, opts_orig, a);
                            if (b !== false) reInit = true;
                            else opts = $.extend(true, {}, opts, a);

                        }
                        else if (!is_undefined(a))
                        {

                            //	callback function for specific option
                            if (is_function(b))
                            {
                                var val = eval('opts.'+a);
                                if (is_undefined(val))
                                {
                                    val = '';
                                }
                                b.call($tt0, val);
                            }
                            //	set individual option
                            else if (!is_undefined(b))
                            {
                                if (typeof c !== 'boolean') c = true;
                                eval('opts_orig.'+a+' = b');
                                if (c !== false) reInit = true;
                                else eval('opts.'+a+' = b');
                            }
                            //	return value for specific option
                            else
                            {
                                return eval('opts.'+a);
                            }
                        }
                        if (reInit)
                        {
                            sz_resetMargin($cfs.children(), opts);
                            $cfs._cfs_init(opts_orig);
                            $cfs._cfs_bind_buttons();
                            var sz = sz_setSizes($cfs, opts);
                            $cfs.trigger(cf_e('updatePageStatus', conf), [true, sz]);
                        }
                        return opts;
                    });


                    //	linkAnchors event
                    $cfs.bind(cf_e('linkAnchors', conf), function(e, $con, sel) {
                        e.stopPropagation();

                        if (is_undefined($con))
                        {
                            $con = $('body');
                        }
                        else if (is_string($con))
                        {
                            $con = $($con);
                        }
                        if (!is_jquery($con) || $con.length == 0)
                        {
                            return debug(conf, 'Not a valid object.');
                        }
                        if (!is_string(sel))
                        {
                            sel = 'a.caroufredsel';
                        }

                        $con.find(sel).each(function() {
                            var h = this.hash || '';
                            if (h.length > 0 && $cfs.children().index($(h)) != -1)
                            {
                                $(this).unbind('click').click(function(e) {
                                    e.preventDefault();
                                    $cfs.trigger(cf_e('slideTo', conf), h);
                                });
                            }
                        });
                        return true;
                    });


                    //	updatePageStatus event
                    $cfs.bind(cf_e('updatePageStatus', conf), function(e, build, sizes) {
                        e.stopPropagation();
                        if (!opts.pagination.container)
                        {
                            return;
                        }

                        var ipp = opts.pagination.items || opts.items.visible,
                            pgs = Math.ceil(itms.total/ipp);

                        if (build)
                        {
                            if (opts.pagination.anchorBuilder)
                            {
                                opts.pagination.container.children().remove();
                                opts.pagination.container.each(function() {
                                    for (var a = 0; a < pgs; a++)
                                    {
                                        var i = $cfs.children().eq( gn_getItemIndex(a*ipp, 0, true, itms, $cfs) );
                                        $(this).append(opts.pagination.anchorBuilder.call(i[0], a+1));
                                    }
                                });
                            }
                            opts.pagination.container.each(function() {
                                $(this).children().unbind(opts.pagination.event).each(function(a) {
                                    $(this).bind(opts.pagination.event, function(e) {
                                        e.preventDefault();
                                        $cfs.trigger(cf_e('slideTo', conf), [a*ipp, -opts.pagination.deviation, true, opts.pagination]);
                                    });
                                });
                            });
                        }

                        var selected = $cfs.triggerHandler(cf_e('currentPage', conf)) + opts.pagination.deviation;
                        if (selected >= pgs)
                        {
                            selected = 0;
                        }
                        if (selected < 0)
                        {
                            selected = pgs-1;
                        }
                        opts.pagination.container.each(function() {
                            $(this).children().removeClass(cf_c('selected', conf)).eq(selected).addClass(cf_c('selected', conf));
                        });
                        return true;
                    });


                    //	updateSizes event
                    $cfs.bind(cf_e('updateSizes', conf), function(e) {
                        var vI = opts.items.visible,
                            a_itm = $cfs.children(),
                            avail_primary = ms_getParentSize($wrp, opts, 'width');

                        itms.total = a_itm.length;

                        if (crsl.primarySizePercentage)
                        {
                            opts.maxDimension = avail_primary;
                            opts[opts.d['width']] = ms_getPercentage(avail_primary, crsl.primarySizePercentage);
                        }
                        else
                        {
                            opts.maxDimension = ms_getMaxDimension(opts, avail_primary);
                        }

                        if (opts.responsive)
                        {
                            opts.items.width = opts.items.sizesConf.width;
                            opts.items.height = opts.items.sizesConf.height;
                            opts = in_getResponsiveValues(opts, a_itm, avail_primary);
                            vI = opts.items.visible;
                            sz_setResponsiveSizes(opts, a_itm);
                        }
                        else if (opts.items.visibleConf.variable)
                        {
                            vI = gn_getVisibleItemsNext(a_itm, opts, 0);
                        }
                        else if (opts.items.filter != '*')
                        {
                            vI = gn_getVisibleItemsNextFilter(a_itm, opts, 0);
                        }

                        if (!opts.circular && itms.first != 0 && vI > itms.first) {
                            if (opts.items.visibleConf.variable)
                            {
                                var nI = gn_getVisibleItemsPrev(a_itm, opts, itms.first) - itms.first;
                            }
                            else if (opts.items.filter != '*')
                            {
                                var nI = gn_getVisibleItemsPrevFilter(a_itm, opts, itms.first) - itms.first;
                            }
                            else
                            {
                                var nI = opts.items.visible - itms.first;
                            }
                            debug(conf, 'Preventing non-circular: sliding '+nI+' items backward.');
                            $cfs.trigger(cf_e('prev', conf), nI);
                        }

                        opts.items.visible = cf_getItemsAdjust(vI, opts, opts.items.visibleConf.adjust, $tt0);
                        opts.items.visibleConf.old = opts.items.visible;
                        opts = in_getAlignPadding(opts, a_itm);

                        var sz = sz_setSizes($cfs, opts);
                        $cfs.trigger(cf_e('updatePageStatus', conf), [true, sz]);
                        nv_showNavi(opts, itms.total, conf);
                        nv_enableNavi(opts, itms.first, conf);

                        return sz;
                    });


                    //	destroy event
                    $cfs.bind(cf_e('destroy', conf), function(e, orgOrder) {
                        e.stopPropagation();
                        tmrs = sc_clearTimers(tmrs);

                        $cfs.data('_cfs_isCarousel', false);
                        $cfs.trigger(cf_e('finish', conf));
                        if (orgOrder)
                        {
                            $cfs.trigger(cf_e('jumpToStart', conf));
                        }
                        sz_resetMargin($cfs.children(), opts);
                        if (opts.responsive)
                        {
                            $cfs.children().each(function() {
                                $(this).css($(this).data('_cfs_origCssSizes'));
                            });
                        }

                        $cfs.css($cfs.data('_cfs_origCss'));
                        $cfs._cfs_unbind_events();
                        $cfs._cfs_unbind_buttons();
                        $wrp.replaceWith($cfs);

                        return true;
                    });


                    //	debug event
                    $cfs.bind(cf_e('debug', conf), function(e) {
                        debug(conf, 'Carousel width: '+opts.width);
                        debug(conf, 'Carousel height: '+opts.height);
                        debug(conf, 'Item widths: '+opts.items.width);
                        debug(conf, 'Item heights: '+opts.items.height);
                        debug(conf, 'Number of items visible: '+opts.items.visible);
                        if (opts.auto.play)
                        {
                            debug(conf, 'Number of items scrolled automatically: '+opts.auto.items);
                        }
                        if (opts.prev.button)
                        {
                            debug(conf, 'Number of items scrolled backward: '+opts.prev.items);
                        }
                        if (opts.next.button)
                        {
                            debug(conf, 'Number of items scrolled forward: '+opts.next.items);
                        }
                        return conf.debug;
                    });


                    //	triggerEvent, making prefixed and namespaced events accessible from outside
                    $cfs.bind('_cfs_triggerEvent', function(e, n, o) {
                        e.stopPropagation();
                        return $cfs.triggerHandler(cf_e(n, conf), o);
                    });
                };	//	/bind_events


                $cfs._cfs_unbind_events = function() {
                    $cfs.unbind(cf_e('', conf));
                    $cfs.unbind(cf_e('', conf, false));
                    $cfs.unbind('_cfs_triggerEvent');
                };	//	/unbind_events


                $cfs._cfs_bind_buttons = function() {
                    $cfs._cfs_unbind_buttons();
                    nv_showNavi(opts, itms.total, conf);
                    nv_enableNavi(opts, itms.first, conf);

                    if (opts.auto.pauseOnHover)
                    {
                        var pC = bt_pauseOnHoverConfig(opts.auto.pauseOnHover);
                        $wrp.bind(cf_e('mouseenter', conf, false), function() { $cfs.trigger(cf_e('pause', conf), pC);	})
                            .bind(cf_e('mouseleave', conf, false), function() { $cfs.trigger(cf_e('resume', conf));		});
                    }

                    //	play button
                    if (opts.auto.button)
                    {
                        opts.auto.button.bind(cf_e(opts.auto.event, conf, false), function(e) {
                            e.preventDefault();
                            var ev = false,
                                pC = null;

                            if (crsl.isPaused)
                            {
                                ev = 'play';
                            }
                            else if (opts.auto.pauseOnEvent)
                            {
                                ev = 'pause';
                                pC = bt_pauseOnHoverConfig(opts.auto.pauseOnEvent);
                            }
                            if (ev)
                            {
                                $cfs.trigger(cf_e(ev, conf), pC);
                            }
                        });
                    }

                    //	prev button
                    if (opts.prev.button)
                    {
                        opts.prev.button.bind(cf_e(opts.prev.event, conf, false), function(e) {
                            e.preventDefault();
                            $cfs.trigger(cf_e('prev', conf));
                        });
                        if (opts.prev.pauseOnHover)
                        {
                            var pC = bt_pauseOnHoverConfig(opts.prev.pauseOnHover);
                            opts.prev.button.bind(cf_e('mouseenter', conf, false), function() { $cfs.trigger(cf_e('pause', conf), pC);	})
                                .bind(cf_e('mouseleave', conf, false), function() { $cfs.trigger(cf_e('resume', conf));		});
                        }
                    }

                    //	next butotn
                    if (opts.next.button)
                    {
                        opts.next.button.bind(cf_e(opts.next.event, conf, false), function(e) {
                            e.preventDefault();
                            $cfs.trigger(cf_e('next', conf));
                        });
                        if (opts.next.pauseOnHover)
                        {
                            var pC = bt_pauseOnHoverConfig(opts.next.pauseOnHover);
                            opts.next.button.bind(cf_e('mouseenter', conf, false), function() { $cfs.trigger(cf_e('pause', conf), pC); 	})
                                .bind(cf_e('mouseleave', conf, false), function() { $cfs.trigger(cf_e('resume', conf));		});
                        }
                    }

                    //	pagination
                    if (opts.pagination.container)
                    {
                        if (opts.pagination.pauseOnHover)
                        {
                            var pC = bt_pauseOnHoverConfig(opts.pagination.pauseOnHover);
                            opts.pagination.container.bind(cf_e('mouseenter', conf, false), function() { $cfs.trigger(cf_e('pause', conf), pC);	})
                                .bind(cf_e('mouseleave', conf, false), function() { $cfs.trigger(cf_e('resume', conf));	});
                        }
                    }

                    //	prev/next keys
                    if (opts.prev.key || opts.next.key)
                    {
                        $(document).bind(cf_e('keyup', conf, false, true, true), function(e) {
                            var k = e.keyCode;
                            if (k == opts.next.key)
                            {
                                e.preventDefault();
                                $cfs.trigger(cf_e('next', conf));
                            }
                            if (k == opts.prev.key)
                            {
                                e.preventDefault();
                                $cfs.trigger(cf_e('prev', conf));
                            }
                        });
                    }

                    //	pagination keys
                    if (opts.pagination.keys)
                    {
                        $(document).bind(cf_e('keyup', conf, false, true, true), function(e) {
                            var k = e.keyCode;
                            if (k >= 49 && k < 58)
                            {
                                k = (k-49) * opts.items.visible;
                                if (k <= itms.total)
                                {
                                    e.preventDefault();
                                    $cfs.trigger(cf_e('slideTo', conf), [k, 0, true, opts.pagination]);
                                }
                            }
                        });
                    }


                    //	DEPRECATED
                    if (opts.prev.wipe || opts.next.wipe)
                    {
                        deprecated( 'the touchwipe-plugin', 'the touchSwipe-plugin' );
                        if ($.fn.touchwipe)
                        {
                            var wP = (opts.prev.wipe) ? function() { $cfs.trigger(cf_e('prev', conf)) } : null,
                                wN = (opts.next.wipe) ? function() { $cfs.trigger(cf_e('next', conf)) } : null;

                            if (wN || wN)
                            {
                                if (!crsl.touchwipe)
                                {
                                    crsl.touchwipe = true;
                                    var twOps = {
                                        'min_move_x': 30,
                                        'min_move_y': 30,
                                        'preventDefaultEvents': true
                                    };
                                    switch (opts.direction)
                                    {
                                        case 'up':
                                        case 'down':
                                            twOps.wipeUp = wP;
                                            twOps.wipeDown = wN;
                                            break;
                                        default:
                                            twOps.wipeLeft = wN;
                                            twOps.wipeRight = wP;
                                    }
                                    $wrp.touchwipe(twOps);
                                }
                            }
                        }
                    }
                    //	/DEPRECATED


                    //	swipe
                    if ($.fn.swipe)
                    {
                        var isTouch = 'ontouchstart' in window;
                        if ((isTouch && opts.swipe.onTouch) || (!isTouch && opts.swipe.onMouse))
                        {
                            var scP = $.extend(true, {}, opts.prev, opts.swipe),
                                scN = $.extend(true, {}, opts.next, opts.swipe),
                                swP = function() { $cfs.trigger(cf_e('prev', conf), [scP]) },
                                swN = function() { $cfs.trigger(cf_e('next', conf), [scN]) };

                            switch (opts.direction)
                            {
                                case 'up':
                                case 'down':
                                    opts.swipe.options.swipeUp = swN;
                                    opts.swipe.options.swipeDown = swP;
                                    break;
                                default:
                                    opts.swipe.options.swipeLeft = swN;
                                    opts.swipe.options.swipeRight = swP;
                            }
                            if (crsl.swipe)
                            {
                                $cfs.swipe('destroy');
                            }
                            $wrp.swipe(opts.swipe.options);
                            $wrp.css('cursor', 'move');
                            crsl.swipe = true;
                        }
                    }

                    //	mousewheel
                    if ($.fn.mousewheel)
                    {


                        //	DEPRECATED
                        if (opts.prev.mousewheel)
                        {
                            deprecated('The prev.mousewheel option', 'the mousewheel configuration object');
                            opts.prev.mousewheel = null;
                            opts.mousewheel = {
                                items: bt_mousesheelNumber(opts.prev.mousewheel)
                            };
                        }
                        if (opts.next.mousewheel)
                        {
                            deprecated('The next.mousewheel option', 'the mousewheel configuration object');
                            opts.next.mousewheel = null;
                            opts.mousewheel = {
                                items: bt_mousesheelNumber(opts.next.mousewheel)
                            };
                        }
                        //	/DEPRECATED


                        if (opts.mousewheel)
                        {
                            var mcP = $.extend(true, {}, opts.prev, opts.mousewheel),
                                mcN = $.extend(true, {}, opts.next, opts.mousewheel);

                            if (crsl.mousewheel)
                            {
                                $wrp.unbind(cf_e('mousewheel', conf, false));
                            }
                            $wrp.bind(cf_e('mousewheel', conf, false), function(e, delta) {
                                e.preventDefault();
                                if (delta > 0)
                                {
                                    $cfs.trigger(cf_e('prev', conf), [mcP]);
                                }
                                else
                                {
                                    $cfs.trigger(cf_e('next', conf), [mcN]);
                                }
                            });
                            crsl.mousewheel = true;
                        }
                    }

                    if (opts.auto.play)
                    {
                        $cfs.trigger(cf_e('play', conf), opts.auto.delay);
                    }

                    if (crsl.upDateOnWindowResize)
                    {
                        var resizeFn = function(e) {
                            $cfs.trigger(cf_e('finish', conf));
                            if (opts.auto.pauseOnResize && !crsl.isPaused)
                            {
                                $cfs.trigger(cf_e('play', conf));
                            }
                            sz_resetMargin($cfs.children(), opts);
                            $cfs.trigger(cf_e('updateSizes', conf));
                        };

                        var $w = $(window),
                            onResize = null;

                        if ($.debounce && conf.onWindowResize == 'debounce')
                        {
                            onResize = $.debounce(200, resizeFn);
                        }
                        else if ($.throttle && conf.onWindowResize == 'throttle')
                        {
                            onResize = $.throttle(300, resizeFn);
                        }
                        else
                        {
                            var _windowWidth = 0,
                                _windowHeight = 0;

                            onResize = function() {
                                var nw = $w.width(),
                                    nh = $w.height();

                                if (nw != _windowWidth || nh != _windowHeight)
                                {
                                    resizeFn();
                                    _windowWidth = nw;
                                    _windowHeight = nh;
                                }
                            };
                        }
                        $w.bind(cf_e('resize', conf, false, true, true), onResize);
                    }
                };	//	/bind_buttons


                $cfs._cfs_unbind_buttons = function() {
                    var ns1 = cf_e('', conf),
                        ns2 = cf_e('', conf, false);
                    ns3 = cf_e('', conf, false, true, true);

                    $(document).unbind(ns3);
                    $(window).unbind(ns3);
                    $wrp.unbind(ns2);

                    if (opts.auto.button)
                    {
                        opts.auto.button.unbind(ns2);
                    }
                    if (opts.prev.button)
                    {
                        opts.prev.button.unbind(ns2);
                    }
                    if (opts.next.button)
                    {
                        opts.next.button.unbind(ns2);
                    }
                    if (opts.pagination.container)
                    {
                        opts.pagination.container.unbind(ns2);
                        if (opts.pagination.anchorBuilder)
                        {
                            opts.pagination.container.children().remove();
                        }
                    }
                    if (crsl.swipe)
                    {
                        $cfs.swipe('destroy');
                        $wrp.css('cursor', 'default');
                        crsl.swipe = false;
                    }
                    if (crsl.mousewheel)
                    {
                        crsl.mousewheel = false;
                    }

                    nv_showNavi(opts, 'hide', conf);
                    nv_enableNavi(opts, 'removeClass', conf);

                };	//	/unbind_buttons



                //	START

                if (is_boolean(configs))
                {
                    configs = {
                        'debug': configs
                    };
                }

                //	set vars
                var crsl = {
                        'direction'		: 'next',
                        'isPaused'		: true,
                        'isScrolling'	: false,
                        'isStopped'		: false,
                        'mousewheel'	: false,
                        'swipe'			: false
                    },
                    itms = {
                        'total'			: $cfs.children().length,
                        'first'			: 0
                    },
                    tmrs = {
                        'auto'			: null,
                        'progress'		: null,
                        'startTime'		: getTime(),
                        'timePassed'	: 0
                    },
                    scrl = {
                        'isStopped'		: false,
                        'duration'		: 0,
                        'startTime'		: 0,
                        'easing'		: '',
                        'anims'			: []
                    },
                    clbk = {
                        'onBefore'		: [],
                        'onAfter'		: []
                    },
                    queu = [],
                    conf = $.extend(true, {}, $.fn.carouFredSel.configs, configs),
                    opts = {},
                    opts_orig = $.extend(true, {}, options),
                    $wrp = $cfs.wrap('<'+conf.wrapper.element+' class="'+conf.wrapper.classname+'" />').parent();


                conf.selector		= $cfs.selector;
                conf.serialNumber	= $.fn.carouFredSel.serialNumber++;


                //	create carousel
                $cfs._cfs_init(opts_orig, true, starting_position);
                $cfs._cfs_build();
                $cfs._cfs_bind_events();
                $cfs._cfs_bind_buttons();

                //	find item to start
                if (is_array(opts.items.start))
                {
                    var start_arr = opts.items.start;
                }
                else
                {
                    var start_arr = [];
                    if (opts.items.start != 0)
                    {
                        start_arr.push(opts.items.start);
                    }
                }
                if (opts.cookie)
                {
                    start_arr.unshift(parseInt(cf_getCookie(opts.cookie), 10));
                }

                if (start_arr.length > 0)
                {
                    for (var a = 0, l = start_arr.length; a < l; a++)
                    {
                        var s = start_arr[a];
                        if (s == 0)
                        {
                            continue;
                        }
                        if (s === true)
                        {
                            s = window.location.hash;
                            if (s.length < 1)
                            {
                                continue;
                            }
                        }
                        else if (s === 'random')
                        {
                            s = Math.floor(Math.random()*itms.total);
                        }
                        if ($cfs.triggerHandler(cf_e('slideTo', conf), [s, 0, true, { fx: 'none' }]))
                        {
                            break;
                        }
                    }
                }
                var siz = sz_setSizes($cfs, opts),
                    itm = gi_getCurrentItems($cfs.children(), opts);

                if (opts.onCreate)
                {
                    opts.onCreate.call($tt0, {
                        'width': siz.width,
                        'height': siz.height,
                        'items': itm
                    });
                }

                $cfs.trigger(cf_e('updatePageStatus', conf), [true, siz]);
                $cfs.trigger(cf_e('linkAnchors', conf));

                if (conf.debug)
                {
                    $cfs.trigger(cf_e('debug', conf));
                }

                return $cfs;
            };



            //	GLOBAL PUBLIC

            $.fn.carouFredSel.serialNumber = 1;
            $.fn.carouFredSel.defaults = {
                'synchronise'	: false,
                'infinite'		: true,
                'circular'		: true,
                'responsive'	: false,
                'direction'		: 'left',
                'items'			: {
                    'start'			: 0
                },
                'scroll'		: {
                    'easing'		: 'swing',
                    'duration'		: 500,
                    'pauseOnHover'	: false,
                    'event'			: 'click',
                    'queue'			: false
                }
            };
            $.fn.carouFredSel.configs = {
                'debug'			: false,
                'onWindowResize': 'throttle',
                'events'		: {
                    'prefix'		: '',
                    'namespace'		: 'cfs'
                },
                'wrapper'		: {
                    'element'		: 'div',
                    'classname'		: 'caroufredsel_wrapper'
                },
                'classnames'	: {}
            };
            $.fn.carouFredSel.pageAnchorBuilder = function(nr) {
                return '<a href="#"><span>'+nr+'</span></a>';
            };
            $.fn.carouFredSel.progressbarUpdater = function(perc) {
                $(this).css('width', perc+'%');
            };

            $.fn.carouFredSel.cookie = {
                get: function(n) {
                    n += '=';
                    var ca = document.cookie.split(';');
                    for (var a = 0, l = ca.length; a < l; a++)
                    {
                        var c = ca[a];
                        while (c.charAt(0) == ' ')
                        {
                            c = c.slice(1);
                        }
                        if (c.indexOf(n) == 0)
                        {
                            return c.slice(n.length);
                        }
                    }
                    return 0;
                },
                set: function(n, v, d) {
                    var e = "";
                    if (d)
                    {
                        var date = new Date();
                        date.setTime(date.getTime() + (d * 24 * 60 * 60 * 1000));
                        e = "; expires=" + date.toGMTString();
                    }
                    document.cookie = n + '=' + v + e + '; path=/';
                },
                remove: function(n) {
                    $.fn.carouFredSel.cookie.set(n, "", -1);
                }
            };


            //	GLOBAL PRIVATE

            //	scrolling functions
            function sc_setScroll(d, e) {
                return {
                    anims: [],
                    duration: d,
                    orgDuration: d,
                    easing: e,
                    startTime: getTime()
                };
            }
            function sc_startScroll(s) {

                if (is_object(s.pre))
                {
                    sc_startScroll(s.pre);
                }
                for (var a = 0, l = s.anims.length; a < l; a++)
                {
                    var b = s.anims[a];
                    if (!b)
                    {
                        continue;
                    }
                    if (b[3])
                    {
                        b[0].stop();
                    }
                    b[0].animate(b[1], {
                        complete: b[2],
                        duration: s.duration,
                        easing: s.easing
                    });
                }
                if (is_object(s.post))
                {
                    sc_startScroll(s.post);
                }
            }
            function sc_stopScroll(s, finish) {
                if (!is_boolean(finish))
                {
                    finish = true;
                }
                if (is_object(s.pre))
                {
                    sc_stopScroll(s.pre, finish);
                }
                for (var a = 0, l = s.anims.length; a < l; a++)
                {
                    var b = s.anims[a];
                    b[0].stop(true);
                    if (finish)
                    {
                        b[0].css(b[1]);
                        if (is_function(b[2]))
                        {
                            b[2]();
                        }
                    }
                }
                if (is_object(s.post))
                {
                    sc_stopScroll(s.post, finish);
                }
            }
            function sc_afterScroll( $c, $c2, o ) {
                if ($c2)
                {
                    $c2.remove();
                }

                switch(o.fx) {
                    case 'fade':
                    case 'crossfade':
                    case 'cover-fade':
                    case 'uncover-fade':
                        $c.css('filter', '');
                        break;
                }
            }
            function sc_fireCallbacks($t, o, b, a, c) {
                if (o[b])
                {
                    o[b].call($t, a);
                }
                if (c[b].length)
                {
                    for (var i = 0, l = c[b].length; i < l; i++)
                    {
                        c[b][i].call($t, a);
                    }
                }
                return [];
            }
            function sc_fireQueue($c, q, c) {

                if (q.length)
                {
                    $c.trigger(cf_e(q[0][0], c), q[0][1]);
                    q.shift();
                }
                return q;
            }
            function sc_hideHiddenItems(hiddenitems) {
                hiddenitems.each(function() {
                    var hi = $(this);
                    hi.data('_cfs_isHidden', hi.is(':hidden')).hide();
                });
            }
            function sc_showHiddenItems(hiddenitems) {
                if (hiddenitems)
                {
                    hiddenitems.each(function() {
                        var hi = $(this);
                        if (!hi.data('_cfs_isHidden'))
                        {
                            hi.show();
                        }
                    });
                }
            }
            function sc_clearTimers(t) {
                if (t.auto)
                {
                    clearTimeout(t.auto);
                }
                if (t.progress)
                {
                    clearInterval(t.progress);
                }
                return t;
            }
            function sc_mapCallbackArguments(i_old, i_skp, i_new, s_itm, s_dir, s_dur, w_siz) {
                return {
                    'width': w_siz.width,
                    'height': w_siz.height,
                    'items': {
                        'old': i_old,
                        'skipped': i_skp,
                        'visible': i_new,

                        //	DEPRECATED
                        'new': i_new
                        //	/DEPRECATED
                    },
                    'scroll': {
                        'items': s_itm,
                        'direction': s_dir,
                        'duration': s_dur
                    }
                };
            }
            function sc_getDuration( sO, o, nI, siz ) {
                var dur = sO.duration;
                if (sO.fx == 'none')
                {
                    return 0;
                }
                if (dur == 'auto')
                {
                    dur = o.scroll.duration / o.scroll.items * nI;
                }
                else if (dur < 10)
                {
                    dur = siz / dur;
                }
                if (dur < 1)
                {
                    return 0;
                }
                if (sO.fx == 'fade')
                {
                    dur = dur / 2;
                }
                return Math.round(dur);
            }

            //	navigation functions
            function nv_showNavi(o, t, c) {
                var minimum = (is_number(o.items.minimum)) ? o.items.minimum : o.items.visible + 1;
                if (t == 'show' || t == 'hide')
                {
                    var f = t;
                }
                else if (minimum > t)
                {
                    debug(c, 'Not enough items ('+t+' total, '+minimum+' needed): Hiding navigation.');
                    var f = 'hide';
                }
                else
                {
                    var f = 'show';
                }
                var s = (f == 'show') ? 'removeClass' : 'addClass',
                    h = cf_c('hidden', c);

                if (o.auto.button)
                {
                    o.auto.button[f]()[s](h);
                }
                if (o.prev.button)
                {
                    o.prev.button[f]()[s](h);
                }
                if (o.next.button)
                {
                    o.next.button[f]()[s](h);
                }
                if (o.pagination.container)
                {
                    o.pagination.container[f]()[s](h);
                }
            }
            function nv_enableNavi(o, f, c) {
                if (o.circular || o.infinite) return;
                var fx = (f == 'removeClass' || f == 'addClass') ? f : false,
                    di = cf_c('disabled', c);

                if (o.auto.button && fx)
                {
                    o.auto.button[fx](di);
                }
                if (o.prev.button)
                {
                    var fn = fx || (f == 0) ? 'addClass' : 'removeClass';
                    o.prev.button[fn](di);
                }
                if (o.next.button)
                {
                    var fn = fx || (f == o.items.visible) ? 'addClass' : 'removeClass';
                    o.next.button[fn](di);
                }
            }

            //	get object functions
            function go_getObject($tt, obj) {
                if (is_function(obj))
                {
                    obj = obj.call($tt);
                }
                else if (is_undefined(obj))
                {
                    obj = {};
                }
                return obj;
            }
            function go_getItemsObject($tt, obj) {
                obj = go_getObject($tt, obj);
                if (is_number(obj))
                {
                    obj	= {
                        'visible': obj
                    };
                }
                else if (obj == 'variable')
                {
                    obj = {
                        'visible': obj,
                        'width': obj,
                        'height': obj
                    };
                }
                else if (!is_object(obj))
                {
                    obj = {};
                }
                return obj;
            }
            function go_getScrollObject($tt, obj) {
                obj = go_getObject($tt, obj);
                if (is_number(obj))
                {
                    if (obj <= 50)
                    {
                        obj = {
                            'items': obj
                        };
                    }
                    else
                    {
                        obj = {
                            'duration': obj
                        };
                    }
                }
                else if (is_string(obj))
                {
                    obj = {
                        'easing': obj
                    };
                }
                else if (!is_object(obj))
                {
                    obj = {};
                }
                return obj;
            }
            function go_getNaviObject($tt, obj) {
                obj = go_getObject($tt, obj);
                if (is_string(obj))
                {
                    var temp = cf_getKeyCode(obj);
                    if (temp == -1)
                    {
                        obj = $(obj);
                    }
                    else
                    {
                        obj = temp;
                    }
                }
                return obj;
            }

            function go_getAutoObject($tt, obj) {
                obj = go_getNaviObject($tt, obj);
                if (is_jquery(obj))
                {
                    obj = {
                        'button': obj
                    };
                }
                else if (is_boolean(obj))
                {
                    obj = {
                        'play': obj
                    };
                }
                else if (is_number(obj))
                {
                    obj = {
                        'timeoutDuration': obj
                    };
                }
                if (obj.progress)
                {
                    if (is_string(obj.progress) || is_jquery(obj.progress))
                    {
                        obj.progress = {
                            'bar': obj.progress
                        };
                    }
                }
                return obj;
            }
            function go_complementAutoObject($tt, obj) {
                if (is_function(obj.button))
                {
                    obj.button = obj.button.call($tt);
                }
                if (is_string(obj.button))
                {
                    obj.button = $(obj.button);
                }
                if (!is_boolean(obj.play))
                {
                    obj.play = true;
                }
                if (!is_number(obj.delay))
                {
                    obj.delay = 0;
                }
                if (is_undefined(obj.pauseOnEvent))
                {
                    obj.pauseOnEvent = true;
                }
                if (!is_boolean(obj.pauseOnResize))
                {
                    obj.pauseOnResize = true;
                }
                if (!is_number(obj.timeoutDuration))
                {
                    obj.timeoutDuration = (obj.duration < 10)
                        ? 2500
                        : obj.duration * 5;
                }
                if (obj.progress)
                {
                    if (is_function(obj.progress.bar))
                    {
                        obj.progress.bar = obj.progress.bar.call($tt);
                    }
                    if (is_string(obj.progress.bar))
                    {
                        obj.progress.bar = $(obj.progress.bar);
                    }
                    if (obj.progress.bar)
                    {
                        if (!is_function(obj.progress.updater))
                        {
                            obj.progress.updater = $.fn.carouFredSel.progressbarUpdater;
                        }
                        if (!is_number(obj.progress.interval))
                        {
                            obj.progress.interval = 50;
                        }
                    }
                    else
                    {
                        obj.progress = false;
                    }
                }
                return obj;
            }

            function go_getPrevNextObject($tt, obj) {
                obj = go_getNaviObject($tt, obj);
                if (is_jquery(obj))
                {
                    obj = {
                        'button': obj
                    };
                }
                else if (is_number(obj))
                {
                    obj = {
                        'key': obj
                    };
                }
                return obj;
            }
            function go_complementPrevNextObject($tt, obj) {
                if (is_function(obj.button))
                {
                    obj.button = obj.button.call($tt);
                }
                if (is_string(obj.button))
                {
                    obj.button = $(obj.button);
                }
                if (is_string(obj.key))
                {
                    obj.key = cf_getKeyCode(obj.key);
                }
                return obj;
            }

            function go_getPaginationObject($tt, obj) {
                obj = go_getNaviObject($tt, obj);
                if (is_jquery(obj))
                {
                    obj = {
                        'container': obj
                    };
                }
                else if (is_boolean(obj))
                {
                    obj = {
                        'keys': obj
                    };
                }
                return obj;
            }
            function go_complementPaginationObject($tt, obj) {
                if (is_function(obj.container))
                {
                    obj.container = obj.container.call($tt);
                }
                if (is_string(obj.container))
                {
                    obj.container = $(obj.container);
                }
                if (!is_number(obj.items))
                {
                    obj.items = false;
                }
                if (!is_boolean(obj.keys))
                {
                    obj.keys = false;
                }
                if (!is_function(obj.anchorBuilder) && !is_false(obj.anchorBuilder))
                {
                    obj.anchorBuilder = $.fn.carouFredSel.pageAnchorBuilder;
                }
                if (!is_number(obj.deviation))
                {
                    obj.deviation = 0;
                }
                return obj;
            }

            function go_getSwipeObject($tt, obj) {
                if (is_function(obj))
                {
                    obj = obj.call($tt);
                }
                if (is_undefined(obj))
                {
                    obj = {
                        'onTouch': false
                    };
                }
                if (is_true(obj))
                {
                    obj = {
                        'onTouch': obj
                    };
                }
                else if (is_number(obj))
                {
                    obj = {
                        'items': obj
                    };
                }
                return obj;
            }
            function go_complementSwipeObject($tt, obj) {
                if (!is_boolean(obj.onTouch))
                {
                    obj.onTouch = true;
                }
                if (!is_boolean(obj.onMouse))
                {
                    obj.onMouse = false;
                }
                if (!is_object(obj.options))
                {
                    obj.options = {};
                }
                if (!is_boolean(obj.options.triggerOnTouchEnd))
                {
                    obj.options.triggerOnTouchEnd = false;
                }
                return obj;
            }
            function go_getMousewheelObject($tt, obj) {
                if (is_function(obj))
                {
                    obj = obj.call($tt);
                }
                if (is_true(obj))
                {
                    obj = {};
                }
                else if (is_number(obj))
                {
                    obj = {
                        'items': obj
                    };
                }
                else if (is_undefined(obj))
                {
                    obj = false;
                }
                return obj;
            }
            function go_complementMousewheelObject($tt, obj) {
                return obj;
            }

            //	get number functions
            function gn_getItemIndex(num, dev, org, items, $cfs) {
                if (is_string(num))
                {
                    num = $(num, $cfs);
                }

                if (is_object(num))
                {
                    num = $(num, $cfs);
                }
                if (is_jquery(num))
                {
                    num = $cfs.children().index(num);
                    if (!is_boolean(org))
                    {
                        org = false;
                    }
                }
                else
                {
                    if (!is_boolean(org))
                    {
                        org = true;
                    }
                }
                if (!is_number(num))
                {
                    num = 0;
                }
                if (!is_number(dev))
                {
                    dev = 0;
                }

                if (org)
                {
                    num += items.first;
                }
                num += dev;
                if (items.total > 0)
                {
                    while (num >= items.total)
                    {
                        num -= items.total;
                    }
                    while (num < 0)
                    {
                        num += items.total;
                    }
                }
                return num;
            }

            //	items prev
            function gn_getVisibleItemsPrev(i, o, s) {
                var t = 0,
                    x = 0;

                for (var a = s; a >= 0; a--)
                {
                    var j = i.eq(a);
                    t += (j.is(':visible')) ? j[o.d['outerWidth']](true) : 0;
                    if (t > o.maxDimension)
                    {
                        return x;
                    }
                    if (a == 0)
                    {
                        a = i.length;
                    }
                    x++;
                }
            }
            function gn_getVisibleItemsPrevFilter(i, o, s) {
                return gn_getItemsPrevFilter(i, o.items.filter, o.items.visibleConf.org, s);
            }
            function gn_getScrollItemsPrevFilter(i, o, s, m) {
                return gn_getItemsPrevFilter(i, o.items.filter, m, s);
            }
            function gn_getItemsPrevFilter(i, f, m, s) {
                var t = 0,
                    x = 0;

                for (var a = s, l = i.length; a >= 0; a--)
                {
                    x++;
                    if (x == l)
                    {
                        return x;
                    }

                    var j = i.eq(a);
                    if (j.is(f))
                    {
                        t++;
                        if (t == m)
                        {
                            return x;
                        }
                    }
                    if (a == 0)
                    {
                        a = l;
                    }
                }
            }

            function gn_getVisibleOrg($c, o) {
                return o.items.visibleConf.org || $c.children().slice(0, o.items.visible).filter(o.items.filter).length;
            }

            //	items next
            function gn_getVisibleItemsNext(i, o, s) {
                var t = 0,
                    x = 0;

                for (var a = s, l = i.length-1; a <= l; a++)
                {
                    var j = i.eq(a);

                    t += (j.is(':visible')) ? j[o.d['outerWidth']](true) : 0;
                    if (t > o.maxDimension)
                    {
                        return x;
                    }

                    x++;
                    if (x == l+1)
                    {
                        return x;
                    }
                    if (a == l)
                    {
                        a = -1;
                    }
                }
            }
            function gn_getVisibleItemsNextTestCircular(i, o, s, l) {
                var v = gn_getVisibleItemsNext(i, o, s);
                if (!o.circular)
                {
                    if (s + v > l)
                    {
                        v = l - s;
                    }
                }
                return v;
            }
            function gn_getVisibleItemsNextFilter(i, o, s) {
                return gn_getItemsNextFilter(i, o.items.filter, o.items.visibleConf.org, s, o.circular);
            }
            function gn_getScrollItemsNextFilter(i, o, s, m) {
                return gn_getItemsNextFilter(i, o.items.filter, m+1, s, o.circular) - 1;
            }
            function gn_getItemsNextFilter(i, f, m, s, c) {
                var t = 0,
                    x = 0;

                for (var a = s, l = i.length-1; a <= l; a++)
                {
                    x++;
                    if (x >= l)
                    {
                        return x;
                    }

                    var j = i.eq(a);
                    if (j.is(f))
                    {
                        t++;
                        if (t == m)
                        {
                            return x;
                        }
                    }
                    if (a == l)
                    {
                        a = -1;
                    }
                }
            }

            //	get items functions
            function gi_getCurrentItems(i, o) {
                return i.slice(0, o.items.visible);
            }
            function gi_getOldItemsPrev(i, o, n) {
                return i.slice(n, o.items.visibleConf.old+n);
            }
            function gi_getNewItemsPrev(i, o) {
                return i.slice(0, o.items.visible);
            }
            function gi_getOldItemsNext(i, o) {
                return i.slice(0, o.items.visibleConf.old);
            }
            function gi_getNewItemsNext(i, o, n) {
                return i.slice(n, o.items.visible+n);
            }

            //	sizes functions
            function sz_storeMargin(i, o, d) {
                if (o.usePadding)
                {
                    if (!is_string(d))
                    {
                        d = '_cfs_origCssMargin';
                    }
                    i.each(function() {
                        var j = $(this),
                            m = parseInt(j.css(o.d['marginRight']), 10);
                        if (!is_number(m))
                        {
                            m = 0;
                        }
                        j.data(d, m);
                    });
                }
            }
            function sz_resetMargin(i, o, m) {
                if (o.usePadding)
                {
                    var x = (is_boolean(m)) ? m : false;
                    if (!is_number(m))
                    {
                        m = 0;
                    }
                    sz_storeMargin(i, o, '_cfs_tempCssMargin');
                    i.each(function() {
                        var j = $(this);
                        j.css(o.d['marginRight'], ((x) ? j.data('_cfs_tempCssMargin') : m + j.data('_cfs_origCssMargin')));
                    });
                }
            }
            function sz_storeSizes(i, o) {
                if (o.responsive)
                {
                    i.each(function() {
                        var j = $(this),
                            s = in_mapCss(j, ['width', 'height']);
                        j.data('_cfs_origCssSizes', s);
                    });
                }
            }
            function sz_setResponsiveSizes(o, all) {
                var visb = o.items.visible,
                    newS = o.items[o.d['width']],
                    seco = o[o.d['height']],
                    secp = is_percentage(seco);

                all.each(function() {
                    var $t = $(this),
                        nw = newS - ms_getPaddingBorderMargin($t, o, 'Width');

                    $t[o.d['width']](nw);
                    if (secp)
                    {
                        $t[o.d['height']](ms_getPercentage(nw, seco));
                    }
                });
            }
            function sz_setSizes($c, o) {
                var $w = $c.parent(),
                    $i = $c.children(),
                    $v = gi_getCurrentItems($i, o),
                    sz = cf_mapWrapperSizes(ms_getSizes($v, o, true), o, false);

                $w.css(sz);

                if (o.usePadding)
                {
                    var p = o.padding,
                        r = p[o.d[1]];

                    if (o.align && r < 0)
                    {
                        r = 0;
                    }
                    var $l = $v.last();
                    $l.css(o.d['marginRight'], $l.data('_cfs_origCssMargin') + r);
                    $c.css(o.d['top'], p[o.d[0]]);
                    $c.css(o.d['left'], p[o.d[3]]);
                }

                $c.css(o.d['width'], sz[o.d['width']]+(ms_getTotalSize($i, o, 'width')*2));
                $c.css(o.d['height'], ms_getLargestSize($i, o, 'height'));
                return sz;
            }

            //	measuring functions
            function ms_getSizes(i, o, wrapper) {
                return [ms_getTotalSize(i, o, 'width', wrapper), ms_getLargestSize(i, o, 'height', wrapper)];
            }
            function ms_getLargestSize(i, o, dim, wrapper) {
                if (!is_boolean(wrapper))
                {
                    wrapper = false;
                }
                if (is_number(o[o.d[dim]]) && wrapper)
                {
                    return o[o.d[dim]];
                }
                if (is_number(o.items[o.d[dim]]))
                {
                    return o.items[o.d[dim]];
                }
                dim = (dim.toLowerCase().indexOf('width') > -1) ? 'outerWidth' : 'outerHeight';
                return ms_getTrueLargestSize(i, o, dim);
            }
            function ms_getTrueLargestSize(i, o, dim) {
                var s = 0;

                for (var a = 0, l = i.length; a < l; a++)
                {
                    var j = i.eq(a);

                    var m = (j.is(':visible')) ? j[o.d[dim]](true) : 0;
                    if (s < m)
                    {
                        s = m;
                    }
                }
                return s;
            }

            function ms_getTotalSize(i, o, dim, wrapper) {
                if (!is_boolean(wrapper))
                {
                    wrapper = false;
                }
                if (is_number(o[o.d[dim]]) && wrapper)
                {
                    return o[o.d[dim]];
                }
                if (is_number(o.items[o.d[dim]]))
                {
                    return o.items[o.d[dim]] * i.length;
                }

                var d = (dim.toLowerCase().indexOf('width') > -1) ? 'outerWidth' : 'outerHeight',
                    s = 0;

                for (var a = 0, l = i.length; a < l; a++)
                {
                    var j = i.eq(a);
                    s += (j.is(':visible')) ? j[o.d[d]](true) : 0;
                }
                return s;
            }
            function ms_getParentSize($w, o, d) {
                var isVisible = $w.is(':visible');
                if (isVisible)
                {
                    $w.hide();
                }
                var s = $w.parent()[o.d[d]]();
                if (isVisible)
                {
                    $w.show();
                }
                return s;
            }
            function ms_getMaxDimension(o, a) {
                return (is_number(o[o.d['width']])) ? o[o.d['width']] : a;
            }
            function ms_hasVariableSizes(i, o, dim) {
                var s = false,
                    v = false;

                for (var a = 0, l = i.length; a < l; a++)
                {
                    var j = i.eq(a);

                    var c = (j.is(':visible')) ? j[o.d[dim]](true) : 0;
                    if (s === false)
                    {
                        s = c;
                    }
                    else if (s != c)
                    {
                        v = true;
                    }
                    if (s == 0)
                    {
                        v = true;
                    }
                }
                return v;
            }
            function ms_getPaddingBorderMargin(i, o, d) {
                return i[o.d['outer'+d]](true) - i[o.d[d.toLowerCase()]]();
            }
            function ms_getPercentage(s, o) {
                if (is_percentage(o))
                {
                    o = parseInt( o.slice(0, -1), 10 );
                    if (!is_number(o))
                    {
                        return s;
                    }
                    s *= o/100;
                }
                return s;
            }

            //	config functions
            function cf_e(n, c, pf, ns, rd) {
                if (!is_boolean(pf))
                {
                    pf = true;
                }
                if (!is_boolean(ns))
                {
                    ns = true;
                }
                if (!is_boolean(rd))
                {
                    rd = false;
                }

                if (pf)
                {
                    n = c.events.prefix + n;
                }
                if (ns)
                {
                    n = n +'.'+ c.events.namespace;
                }
                if (ns && rd)
                {
                    n += c.serialNumber;
                }

                return n;
            }
            function cf_c(n, c) {
                return (is_string(c.classnames[n])) ? c.classnames[n] : n;
            }
            function cf_mapWrapperSizes(ws, o, p) {
                if (!is_boolean(p))
                {
                    p = true;
                }
                var pad = (o.usePadding && p) ? o.padding : [0, 0, 0, 0];
                var wra = {};

                wra[o.d['width']] = ws[0] + pad[1] + pad[3];
                wra[o.d['height']] = ws[1] + pad[0] + pad[2];

                return wra;
            }
            function cf_sortParams(vals, typs) {
                var arr = [];
                for (var a = 0, l1 = vals.length; a < l1; a++)
                {
                    for (var b = 0, l2 = typs.length; b < l2; b++)
                    {
                        if (typs[b].indexOf(typeof vals[a]) > -1 && is_undefined(arr[b]))
                        {
                            arr[b] = vals[a];
                            break;
                        }
                    }
                }
                return arr;
            }
            function cf_getPadding(p) {
                if (is_undefined(p))
                {
                    return [0, 0, 0, 0];
                }
                if (is_number(p))
                {
                    return [p, p, p, p];
                }
                if (is_string(p))
                {
                    p = p.split('px').join('').split('em').join('').split(' ');
                }

                if (!is_array(p))
                {
                    return [0, 0, 0, 0];
                }
                for (var i = 0; i < 4; i++)
                {
                    p[i] = parseInt(p[i], 10);
                }
                switch (p.length)
                {
                    case 0:
                        return [0, 0, 0, 0];
                    case 1:
                        return [p[0], p[0], p[0], p[0]];
                    case 2:
                        return [p[0], p[1], p[0], p[1]];
                    case 3:
                        return [p[0], p[1], p[2], p[1]];
                    default:
                        return [p[0], p[1], p[2], p[3]];
                }
            }
            function cf_getAlignPadding(itm, o) {
                var x = (is_number(o[o.d['width']])) ? Math.ceil(o[o.d['width']] - ms_getTotalSize(itm, o, 'width')) : 0;
                switch (o.align)
                {
                    case 'left':
                        return [0, x];
                    case 'right':
                        return [x, 0];
                    case 'center':
                    default:
                        return [Math.ceil(x/2), Math.floor(x/2)];
                }
            }
            function cf_getDimensions(o) {
                var dm = [
                    ['width'	, 'innerWidth'	, 'outerWidth'	, 'height'	, 'innerHeight'	, 'outerHeight'	, 'left', 'top'	, 'marginRight'	, 0, 1, 2, 3],
                    ['height'	, 'innerHeight'	, 'outerHeight'	, 'width'	, 'innerWidth'	, 'outerWidth'	, 'top'	, 'left', 'marginBottom', 3, 2, 1, 0]
                ];

                var dl = dm[0].length,
                    dx = (o.direction == 'right' || o.direction == 'left') ? 0 : 1;

                var dimensions = {};
                for (var d = 0; d < dl; d++)
                {
                    dimensions[dm[0][d]] = dm[dx][d];
                }
                return dimensions;
            }
            function cf_getAdjust(x, o, a, $t) {
                var v = x;
                if (is_function(a))
                {
                    v = a.call($t, v);

                }
                else if (is_string(a))
                {
                    var p = a.split('+'),
                        m = a.split('-');

                    if (m.length > p.length)
                    {
                        var neg = true,
                            sta = m[0],
                            adj = m[1];
                    }
                    else
                    {
                        var neg = false,
                            sta = p[0],
                            adj = p[1];
                    }

                    switch(sta)
                    {
                        case 'even':
                            v = (x % 2 == 1) ? x-1 : x;
                            break;
                        case 'odd':
                            v = (x % 2 == 0) ? x-1 : x;
                            break;
                        default:
                            v = x;
                            break;
                    }
                    adj = parseInt(adj, 10);
                    if (is_number(adj))
                    {
                        if (neg)
                        {
                            adj = -adj;
                        }
                        v += adj;
                    }
                }
                if (!is_number(v) || v < 1)
                {
                    v = 1;
                }
                return v;
            }
            function cf_getItemsAdjust(x, o, a, $t) {
                return cf_getItemAdjustMinMax(cf_getAdjust(x, o, a, $t), o.items.visibleConf);
            }
            function cf_getItemAdjustMinMax(v, i) {
                if (is_number(i.min) && v < i.min)
                {
                    v = i.min;
                }
                if (is_number(i.max) && v > i.max)
                {
                    v = i.max;
                }
                if (v < 1)
                {
                    v = 1;
                }
                return v;
            }
            function cf_getSynchArr(s) {
                if (!is_array(s))
                {
                    s = [[s]];
                }
                if (!is_array(s[0]))
                {
                    s = [s];
                }
                for (var j = 0, l = s.length; j < l; j++)
                {
                    if (is_string(s[j][0]))
                    {
                        s[j][0] = $(s[j][0]);
                    }
                    if (!is_boolean(s[j][1]))
                    {
                        s[j][1] = true;
                    }
                    if (!is_boolean(s[j][2]))
                    {
                        s[j][2] = true;
                    }
                    if (!is_number(s[j][3]))
                    {
                        s[j][3] = 0;
                    }
                }
                return s;
            }
            function cf_getKeyCode(k) {
                if (k == 'right')
                {
                    return 39;
                }
                if (k == 'left')
                {
                    return 37;
                }
                if (k == 'up')
                {
                    return 38;
                }
                if (k == 'down')
                {
                    return 40;
                }
                return -1;
            }
            function cf_setCookie(n, $c, c) {
                if (n)
                {
                    var v = $c.triggerHandler(cf_e('currentPosition', c));
                    $.fn.carouFredSel.cookie.set(n, v);
                }
            }
            function cf_getCookie(n) {
                var c = $.fn.carouFredSel.cookie.get(n);
                return (c == '') ? 0 : c;
            }

            //	init function
            function in_mapCss($elem, props) {
                var css = {}, prop;
                for (var p = 0, l = props.length; p < l; p++)
                {
                    prop = props[p];
                    css[prop] = $elem.css(prop);
                }
                return css;
            }
            function in_complementItems(obj, opt, itm, sta) {
                if (!is_object(obj.visibleConf))
                {
                    obj.visibleConf = {};
                }
                if (!is_object(obj.sizesConf))
                {
                    obj.sizesConf = {};
                }

                if (obj.start == 0 && is_number(sta))
                {
                    obj.start = sta;
                }

                //	visible items
                if (is_object(obj.visible))
                {
                    obj.visibleConf.min = obj.visible.min;
                    obj.visibleConf.max = obj.visible.max;
                    obj.visible = false;
                }
                else if (is_string(obj.visible))
                {
                    //	variable visible items
                    if (obj.visible == 'variable')
                    {
                        obj.visibleConf.variable = true;
                    }
                    //	adjust string visible items
                    else
                    {
                        obj.visibleConf.adjust = obj.visible;
                    }
                    obj.visible = false;
                }
                else if (is_function(obj.visible))
                {
                    obj.visibleConf.adjust = obj.visible;
                    obj.visible = false;
                }

                //	set items filter
                if (!is_string(obj.filter))
                {
                    obj.filter = (itm.filter(':hidden').length > 0) ? ':visible' : '*';
                }

                //	primary item-size not set
                if (!obj[opt.d['width']])
                {
                    //	responsive carousel -> set to largest
                    if (opt.responsive)
                    {
                        debug(true, 'Set a '+opt.d['width']+' for the items!');
                        obj[opt.d['width']] = ms_getTrueLargestSize(itm, opt, 'outerWidth');
                    }
                    //	 non-responsive -> measure it or set to "variable"
                    else
                    {
                        obj[opt.d['width']] = (ms_hasVariableSizes(itm, opt, 'outerWidth'))
                            ? 'variable'
                            : itm[opt.d['outerWidth']](true);
                    }
                }

                //	secondary item-size not set -> measure it or set to "variable"
                if (!obj[opt.d['height']])
                {
                    obj[opt.d['height']] = (ms_hasVariableSizes(itm, opt, 'outerHeight'))
                        ? 'variable'
                        : itm[opt.d['outerHeight']](true);
                }

                obj.sizesConf.width = obj.width;
                obj.sizesConf.height = obj.height;
                return obj;
            }
            function in_complementVisibleItems(opt, avl) {
                //	primary item-size variable -> set visible items variable
                if (opt.items[opt.d['width']] == 'variable')
                {
                    opt.items.visibleConf.variable = true;
                }
                if (!opt.items.visibleConf.variable) {
                    //	primary size is number -> calculate visible-items
                    if (is_number(opt[opt.d['width']]))
                    {
                        opt.items.visible = Math.floor(opt[opt.d['width']] / opt.items[opt.d['width']]);
                    }
                    //	measure and calculate primary size and visible-items
                    else
                    {
                        opt.items.visible = Math.floor(avl / opt.items[opt.d['width']]);
                        opt[opt.d['width']] = opt.items.visible * opt.items[opt.d['width']];
                        if (!opt.items.visibleConf.adjust)
                        {
                            opt.align = false;
                        }
                    }
                    if (opt.items.visible == 'Infinity' || opt.items.visible < 1)
                    {
                        debug(true, 'Not a valid number of visible items: Set to "variable".');
                        opt.items.visibleConf.variable = true;
                    }
                }
                return opt;
            }
            function in_complementPrimarySize(obj, opt, all) {
                //	primary size set to auto -> measure largest item-size and set it
                if (obj == 'auto')
                {
                    obj = ms_getTrueLargestSize(all, opt, 'outerWidth');
                }
                return obj;
            }
            function in_complementSecondarySize(obj, opt, all) {
                //	secondary size set to auto -> measure largest item-size and set it
                if (obj == 'auto')
                {
                    obj = ms_getTrueLargestSize(all, opt, 'outerHeight');
                }
                //	secondary size not set -> set to secondary item-size
                if (!obj)
                {
                    obj = opt.items[opt.d['height']];
                }
                return obj;
            }
            function in_getAlignPadding(o, all) {
                var p = cf_getAlignPadding(gi_getCurrentItems(all, o), o);
                o.padding[o.d[1]] = p[1];
                o.padding[o.d[3]] = p[0];
                return o;
            }
            function in_getResponsiveValues(o, all, avl) {

                var visb = cf_getItemAdjustMinMax(Math.ceil(o[o.d['width']] / o.items[o.d['width']]), o.items.visibleConf);
                if (visb > all.length)
                {
                    visb = all.length;
                }

                var newS = Math.floor(o[o.d['width']]/visb);

                o.items.visible = visb;
                o.items[o.d['width']] = newS;
                o[o.d['width']] = visb * newS;
                return o;
            }


            //	buttons functions
            function bt_pauseOnHoverConfig(p) {
                if (is_string(p))
                {
                    var i = (p.indexOf('immediate') > -1) ? true : false,
                        r = (p.indexOf('resume') 	> -1) ? true : false;
                }
                else
                {
                    var i = r = false;
                }
                return [i, r];
            }
            function bt_mousesheelNumber(mw) {
                return (is_number(mw)) ? mw : null
            }

            //	helper functions
            function is_null(a) {
                return (a === null);
            }
            function is_undefined(a) {
                return (is_null(a) || typeof a == 'undefined' || a === '' || a === 'undefined');
            }
            function is_array(a) {
                return (a instanceof Array);
            }
            function is_jquery(a) {
                return (a instanceof jQuery);
            }
            function is_object(a) {
                return ((a instanceof Object || typeof a == 'object') && !is_null(a) && !is_jquery(a) && !is_array(a));
            }
            function is_number(a) {
                return ((a instanceof Number || typeof a == 'number') && !isNaN(a));
            }
            function is_string(a) {
                return ((a instanceof String || typeof a == 'string') && !is_undefined(a) && !is_true(a) && !is_false(a));
            }
            function is_function(a) {
                return (a instanceof Function || typeof a == 'function');
            }
            function is_boolean(a) {
                return (a instanceof Boolean || typeof a == 'boolean' || is_true(a) || is_false(a));
            }
            function is_true(a) {
                return (a === true || a === 'true');
            }
            function is_false(a) {
                return (a === false || a === 'false');
            }
            function is_percentage(x) {
                return (is_string(x) && x.slice(-1) == '%');
            }


            function getTime() {
                return new Date().getTime();
            }

            function deprecated( o, n ) {
                debug(true, o+' is DEPRECATED, support for it will be removed. Use '+n+' instead.');
            }
            function debug(d, m) {
                if (is_object(d))
                {
                    var s = ' ('+d.selector+')';
                    d = d.debug;
                }
                else
                {
                    var s = '';
                }
                if (!d)
                {
                    return false;
                }

                if (is_string(m))
                {
                    m = 'carouFredSel'+s+': ' + m;
                }
                else
                {
                    m = ['carouFredSel'+s+':', m];
                }

                if (window.console && window.console.log)
                {
                    window.console.log(m);
                }
                return false;
            }



            //	EASING FUNCTIONS

            $.extend($.easing, {
                'quadratic': function(t) {
                    var t2 = t * t;
                    return t * (-t2 * t + 4 * t2 - 6 * t + 4);
                },
                'cubic': function(t) {
                    return t * (4 * t * t - 9 * t + 6);
                },
                'elastic': function(t) {
                    var t2 = t * t;
                    return t * (33 * t2 * t2 - 106 * t2 * t + 126 * t2 - 67 * t + 15);
                }
            });


        })(jQuery);
    </script>
    <script>
        (function( $, undefined ) {

            /*
             * Slider object.
             */
            $.Slider 				= function( options, element ) {

                this.$el	= $( element );

                this._init( options );

            };

            $.Slider.defaults 		= {
                current		: 0, 	// index of current slide
                bgincrement	: 50,	// increment the bg position (parallax effect) when sliding
                autoplay	: false,// slideshow on / off
                interval	: 4000  // time between transitions
            };

            $.Slider.prototype 	= {
                _init 				: function( options ) {

                    this.options 		= $.extend( true, {}, $.Slider.defaults, options );

                    this.$slides		= this.$el.children('div.da-slide');
                    this.slidesCount	= this.$slides.length;

                    this.current		= this.options.current;

                    if( this.current < 0 || this.current >= this.slidesCount ) {

                        this.current	= 0;

                    }

                    this.$slides.eq( this.current ).addClass( 'da-slide-current' );

                    var $navigation		= $( '<nav class="da-dots"/>' );
                    for( var i = 0; i < this.slidesCount; ++i ) {

                        $navigation.append( '<span/>' );

                    }
                    $navigation.appendTo( this.$el );

                    this.$pages			= this.$el.find('nav.da-dots > span');
                    this.$navNext		= this.$el.find('span.da-arrows-next');
                    this.$navPrev		= this.$el.find('span.da-arrows-prev');

                    this.isAnimating	= false;

                    this.bgpositer		= 0;

                    this.cssAnimations	= Modernizr.cssanimations;
                    this.cssTransitions	= Modernizr.csstransitions;

                    if( !this.cssAnimations || !this.cssAnimations ) {

                        this.$el.addClass( 'da-slider-fb' );

                    }

                    this._updatePage();

                    // load the events
                    this._loadEvents();

                    // slideshow
                    if( this.options.autoplay ) {

                        this._startSlideshow();

                    }

                },
                _navigate			: function( page, dir ) {

                    var $current	= this.$slides.eq( this.current ), $next, _self = this;

                    if( this.current === page || this.isAnimating ) return false;

                    this.isAnimating	= true;

                    // check dir
                    var classTo, classFrom, d;

                    if( !dir ) {

                        ( page > this.current ) ? d = 'next' : d = 'prev';

                    }
                    else {

                        d = dir;

                    }

                    if( this.cssAnimations && this.cssAnimations ) {

                        if( d === 'next' ) {

                            classTo		= 'da-slide-toleft';
                            classFrom	= 'da-slide-fromright';
                            ++this.bgpositer;

                        }
                        else {

                            classTo		= 'da-slide-toright';
                            classFrom	= 'da-slide-fromleft';
                            --this.bgpositer;

                        }

                        this.$el.css( 'background-position' , this.bgpositer * this.options.bgincrement + '% 0%' );

                    }

                    this.current	= page;

                    $next			= this.$slides.eq( this.current );

                    if( this.cssAnimations && this.cssAnimations ) {

                        var rmClasses	= 'da-slide-toleft da-slide-toright da-slide-fromleft da-slide-fromright';
                        $current.removeClass( rmClasses );
                        $next.removeClass( rmClasses );

                        $current.addClass( classTo );
                        $next.addClass( classFrom );

                        $current.removeClass( 'da-slide-current' );
                        $next.addClass( 'da-slide-current' );

                    }

                    // fallback
                    if( !this.cssAnimations || !this.cssAnimations ) {

                        $next.css( 'left', ( d === 'next' ) ? '100%' : '-100%' ).stop().animate( {
                            left : '0%'
                        }, 1000, function() {
                            _self.isAnimating = false;
                        });

                        $current.stop().animate( {
                            left : ( d === 'next' ) ? '-100%' : '100%'
                        }, 1000, function() {
                            $current.removeClass( 'da-slide-current' );
                        });

                    }

                    this._updatePage();

                },
                _updatePage			: function() {

                    this.$pages.removeClass( 'da-dots-current' );
                    this.$pages.eq( this.current ).addClass( 'da-dots-current' );

                },
                _startSlideshow		: function() {

                    var _self	= this;

                    this.slideshow	= setTimeout( function() {

                        var page = ( _self.current < _self.slidesCount - 1 ) ? page = _self.current + 1 : page = 0;
                        _self._navigate( page, 'next' );

                        if( _self.options.autoplay ) {

                            _self._startSlideshow();

                        }

                    }, this.options.interval );

                },
                page				: function( idx ) {

                    if( idx >= this.slidesCount || idx < 0 ) {

                        return false;

                    }

                    if( this.options.autoplay ) {

                        clearTimeout( this.slideshow );
                        this.options.autoplay	= false;

                    }

                    this._navigate( idx );

                },
                _loadEvents			: function() {

                    var _self = this;

                    this.$pages.on( 'click.cslider', function( event ) {

                        _self.page( $(this).index() );
                        return false;

                    });

                    this.$navNext.on( 'click.cslider', function( event ) {

                        if( _self.options.autoplay ) {

                            clearTimeout( _self.slideshow );
                            _self.options.autoplay	= false;

                        }

                        var page = ( _self.current < _self.slidesCount - 1 ) ? page = _self.current + 1 : page = 0;
                        _self._navigate( page, 'next' );
                        return false;

                    });

                    this.$navPrev.on( 'click.cslider', function( event ) {

                        if( _self.options.autoplay ) {

                            clearTimeout( _self.slideshow );
                            _self.options.autoplay	= false;

                        }

                        var page = ( _self.current > 0 ) ? page = _self.current - 1 : page = _self.slidesCount - 1;
                        _self._navigate( page, 'prev' );
                        return false;

                    });

                    if( this.cssTransitions ) {

                        if( !this.options.bgincrement ) {

                            this.$el.on( 'webkitAnimationEnd.cslider animationend.cslider OAnimationEnd.cslider', function( event ) {

                                if( event.originalEvent.animationName === 'toRightAnim4' || event.originalEvent.animationName === 'toLeftAnim4' ) {

                                    _self.isAnimating	= false;

                                }

                            });

                        }
                        else {

                            this.$el.on( 'webkitTransitionEnd.cslider transitionend.cslider OTransitionEnd.cslider', function( event ) {

                                if( event.target.id === _self.$el.attr( 'id' ) )
                                    _self.isAnimating	= false;

                            });

                        }

                    }

                }
            };

            var logError 			= function( message ) {
                if ( this.console ) {
                    console.error( message );
                }
            };

            $.fn.cslider			= function( options ) {

                if ( typeof options === 'string' ) {

                    var args = Array.prototype.slice.call( arguments, 1 );

                    this.each(function() {

                        var instance = $.data( this, 'cslider' );

                        if ( !instance ) {
                            logError( "cannot call methods on cslider prior to initialization; " +
                                "attempted to call method '" + options + "'" );
                            return;
                        }

                        if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
                            logError( "no such method '" + options + "' for cslider instance" );
                            return;
                        }

                        instance[ options ].apply( instance, args );

                    });

                }
                else {

                    this.each(function() {

                        var instance = $.data( this, 'cslider' );
                        if ( !instance ) {
                            $.data( this, 'cslider', new $.Slider( options, this ) );
                        }
                    });

                }

                return this;

            };

        })( jQuery );
    </script>
    <script>

        /* Modernizr 2.5.3 (Custom Build) | MIT & BSD
         * Build: http://www.modernizr.com/download/#-cssanimations-csstransitions-shiv-cssclasses-testprop-testallprops-domprefixes-load
         */
        ;window.Modernizr=function(a,b,c){function x(a){j.cssText=a}function y(a,b){return x(prefixes.join(a+";")+(b||""))}function z(a,b){return typeof a===b}function A(a,b){return!!~(""+a).indexOf(b)}function B(a,b){for(var d in a)if(j[a[d]]!==c)return b=="pfx"?a[d]:!0;return!1}function C(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:z(f,"function")?f.bind(d||b):f}return!1}function D(a,b,c){var d=a.charAt(0).toUpperCase()+a.substr(1),e=(a+" "+n.join(d+" ")+d).split(" ");return z(b,"string")||z(b,"undefined")?B(e,b):(e=(a+" "+o.join(d+" ")+d).split(" "),C(e,b,c))}var d="2.5.3",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m="Webkit Moz O ms",n=m.split(" "),o=m.toLowerCase().split(" "),p={},q={},r={},s=[],t=s.slice,u,v={}.hasOwnProperty,w;!z(v,"undefined")&&!z(v.call,"undefined")?w=function(a,b){return v.call(a,b)}:w=function(a,b){return b in a&&z(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=t.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(t.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(t.call(arguments)))};return e}),p.cssanimations=function(){return D("animationName")},p.csstransitions=function(){return D("transition")};for(var E in p)w(p,E)&&(u=E.toLowerCase(),e[u]=p[E](),s.push((e[u]?"":"no-")+u));return x(""),i=k=null,function(a,b){function g(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function h(){var a=k.elements;return typeof a=="string"?a.split(" "):a}function i(a){var b={},c=a.createElement,e=a.createDocumentFragment,f=e();a.createElement=function(a){var e=(b[a]||(b[a]=c(a))).cloneNode();return k.shivMethods&&e.canHaveChildren&&!d.test(a)?f.appendChild(e):e},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+h().join().replace(/\w+/g,function(a){return b[a]=c(a),f.createElement(a),'c("'+a+'")'})+");return n}")(k,f)}function j(a){var b;return a.documentShived?a:(k.shivCSS&&!e&&(b=!!g(a,"article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block}audio{display:none}canvas,video{display:inline-block;*display:inline;*zoom:1}[hidden]{display:none}audio[controls]{display:inline-block;*display:inline;*zoom:1}mark{background:#FF0;color:#000}")),f||(b=!i(a)),b&&(a.documentShived=b),a)}var c=a.html5||{},d=/^<|^(?:button|form|map|select|textarea)$/i,e,f;(function(){var a=b.createElement("a");a.innerHTML="<xyz></xyz>",e="hidden"in a,f=a.childNodes.length==1||function(){try{b.createElement("a")}catch(a){return!0}var c=b.createDocumentFragment();return typeof c.cloneNode=="undefined"||typeof c.createDocumentFragment=="undefined"||typeof c.createElement=="undefined"}()})();var k={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:j};a.html5=k,j(b)}(this,b),e._version=d,e._domPrefixes=o,e._cssomPrefixes=n,e.testProp=function(a){return B([a])},e.testAllProps=D,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+s.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return o.call(a)=="[object Function]"}function e(a){return typeof a=="string"}function f(){}function g(a){return!a||a=="loaded"||a=="complete"||a=="uninitialized"}function h(){var a=p.shift();q=1,a?a.t?m(function(){(a.t=="c"?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){a!="img"&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l={},o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};y[c]===1&&(r=1,y[c]=[],l=b.createElement(a)),a=="object"?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),a!="img"&&(r||y[c]===2?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i(b=="c"?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),p.length==1&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&o.call(a.opera)=="[object Opera]",l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return o.call(a)=="[object Array]"},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,i){var j=b(a),l=j.autoCallback;j.url.split(".").pop().split("?").shift(),j.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]||h),j.instead?j.instead(a,e,f,g,i):(y[j.url]?j.noexec=!0:y[j.url]=1,f.load(j.url,j.forceCSS||!j.forceJS&&"css"==j.url.split(".").pop().split("?").shift()?"c":c,j.noexec,j.attrs,j.timeout),(d(e)||d(l))&&f.load(function(){k(),e&&e(j.origUrl,i,g),l&&l(j.origUrl,i,g),y[j.url]=2})))}function i(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var j,l,m=this.yepnope.loader;if(e(a))g(a,0,m,0);else if(w(a))for(j=0;j<a.length;j++)l=a[j],e(l)?g(l,0,m,0):w(l)?B(l):Object(l)===l&&i(l,m);else Object(a)===a&&i(a,m)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,b.readyState==null&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};
    </script>
    <script>
        /*
         * NAME:	jQuery Twitter Feed Function
         * AUTHOR:	Jay Blanchard
         * DATE:	2011-09-25
         *
         * USAGE:	Include to call tweets into a web page
         *
         * NOTE:	Different than the function in the book, going after a certain #hashtag
         */
        $(document).ready(function() {
            $.getJSON('http://search.twitter.com/search.json?rpp=75&callback=?&q=%23themeforest' ,function(data){
                for(var i=0;i<data.results.length;i++){
                    var tweeter = data.results[i].from_user;
                    var tweetText = data.results[i].text;
                    var tweetText = tweetText.substring(0, 120);
                    tweetText = tweetText.replace(/http:\/\/\S+/g, '<a href="$&" target="_blank">$&</a>');
                    tweetText = tweetText.replace(/(@)(\w+)/g, ' $1<a href="http://twitter.com/$2" target="_blank">$2</a>');
                    tweetText = tweetText.replace(/(#)(\w+)/g, ' $1<a href="http://search.twitter.com/search?q=%23$2" target="_blank">$2</a>');
                    $('#tw').append('<li class="tweet"><div class="tweetImage"><a href="http://twitter.com/'+tweeter+'" target="_blank">@'+tweeter+'</a></div><div class="tweetBody">'+tweetText+'</div></li>');
                }
            });

            function autoScroll() {
                var itemHeight = $('#tw li').outerHeight();
                /* calculte how much to move the scroller */
                var moveFactor = parseInt($('#tw').css('top')) + itemHeight;
                /* animate the carousel */
                $('#tw').animate(
                    {'top' : moveFactor}, 'slow', 'linear', function(){
                        /* put the last item before the first item */
                        $("#tw li:first").before($("#tw li:last"));
                        /* reset top position */
                        $('#tw').css({'top' : '-6em'});
                    });
            };
            /* make the carousel scroll automatically when the page loads */
            var moveScroll = setInterval(autoScroll, 6000);
        });
    </script>
    <script type="text/javascript" >
        /*!
         * fancyBox - jQuery Plugin
         * version: 2.1.3 (Tue, 23 Oct 2012)
         * @requires jQuery v1.6 or later
         *
         * Examples at http://fancyapps.com/fancybox/
         * License: www.fancyapps.com/fancybox/#license
         *
         * Copyright 2012 Janis Skarnelis - janis@fancyapps.com
         *
         */

        (function (window, document, $, undefined) {
            "use strict";

            var W = $(window),
                D = $(document),
                F = $.fancybox = function () {
                    F.open.apply( this, arguments );
                },
                didUpdate = null,
                isTouch	  = document.createTouch !== undefined,

                isQuery	= function(obj) {
                    return obj && obj.hasOwnProperty && obj instanceof $;
                },
                isString = function(str) {
                    return str && $.type(str) === "string";
                },
                isPercentage = function(str) {
                    return isString(str) && str.indexOf('%') > 0;
                },
                isScrollable = function(el) {
                    return (el && !(el.style.overflow && el.style.overflow === 'hidden') && ((el.clientWidth && el.scrollWidth > el.clientWidth) || (el.clientHeight && el.scrollHeight > el.clientHeight)));
                },
                getScalar = function(orig, dim) {
                    var value = parseInt(orig, 10) || 0;

                    if (dim && isPercentage(orig)) {
                        value = F.getViewport()[ dim ] / 100 * value;
                    }

                    return Math.ceil(value);
                },
                getValue = function(value, dim) {
                    return getScalar(value, dim) + 'px';
                };

            $.extend(F, {
                // The current version of fancyBox
                version: '2.1.3',

                defaults: {
                    padding : 15,
                    margin  : 20,

                    width     : 800,
                    height    : 600,
                    minWidth  : 100,
                    minHeight : 100,
                    maxWidth  : 9999,
                    maxHeight : 9999,

                    autoSize   : true,
                    autoHeight : false,
                    autoWidth  : false,

                    autoResize  : true,
                    autoCenter  : !isTouch,
                    fitToView   : true,
                    aspectRatio : false,
                    topRatio    : 0.5,
                    leftRatio   : 0.5,

                    scrolling : 'auto', // 'auto', 'yes' or 'no'
                    wrapCSS   : '',

                    arrows     : true,
                    closeBtn   : true,
                    closeClick : false,
                    nextClick  : false,
                    mouseWheel : true,
                    autoPlay   : false,
                    playSpeed  : 3000,
                    preload    : 3,
                    modal      : false,
                    loop       : true,

                    ajax  : {
                        dataType : 'html',
                        headers  : { 'X-fancyBox': true }
                    },
                    iframe : {
                        scrolling : 'auto',
                        preload   : true
                    },
                    swf : {
                        wmode: 'transparent',
                        allowfullscreen   : 'true',
                        allowscriptaccess : 'always'
                    },

                    keys  : {
                        next : {
                            13 : 'left', // enter
                            34 : 'up',   // page down
                            39 : 'left', // right arrow
                            40 : 'up'    // down arrow
                        },
                        prev : {
                            8  : 'right',  // backspace
                            33 : 'down',   // page up
                            37 : 'right',  // left arrow
                            38 : 'down'    // up arrow
                        },
                        close  : [27], // escape key
                        play   : [32], // space - start/stop slideshow
                        toggle : [70]  // letter "f" - toggle fullscreen
                    },

                    direction : {
                        next : 'left',
                        prev : 'right'
                    },

                    scrollOutside  : true,

                    // Override some properties
                    index   : 0,
                    type    : null,
                    href    : null,
                    content : null,
                    title   : null,

                    // HTML templates
                    tpl: {
                        wrap     : '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
                        image    : '<img alt="alt_example" class="fancybox-image" src="{href}"  />',
                        iframe   : '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen' + ($.browser.msie ? ' ' : '') + '></iframe>',
                        error    : '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                        closeBtn : '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
                        next     : '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
                        prev     : '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
                    },

                    // Properties for each animation type
                    // Opening fancyBox
                    openEffect  : 'fade', // 'elastic', 'fade' or 'none'
                    openSpeed   : 250,
                    openEasing  : 'swing',
                    openOpacity : true,
                    openMethod  : 'zoomIn',

                    // Closing fancyBox
                    closeEffect  : 'fade', // 'elastic', 'fade' or 'none'
                    closeSpeed   : 250,
                    closeEasing  : 'swing',
                    closeOpacity : true,
                    closeMethod  : 'zoomOut',

                    // Changing next gallery item
                    nextEffect : 'elastic', // 'elastic', 'fade' or 'none'
                    nextSpeed  : 250,
                    nextEasing : 'swing',
                    nextMethod : 'changeIn',

                    // Changing previous gallery item
                    prevEffect : 'elastic', // 'elastic', 'fade' or 'none'
                    prevSpeed  : 250,
                    prevEasing : 'swing',
                    prevMethod : 'changeOut',

                    // Enable default helpers
                    helpers : {
                        overlay : true,
                        title   : true
                    },

                    // Callbacks
                    onCancel     : $.noop, // If canceling
                    beforeLoad   : $.noop, // Before loading
                    afterLoad    : $.noop, // After loading
                    beforeShow   : $.noop, // Before changing in current item
                    afterShow    : $.noop, // After opening
                    beforeChange : $.noop, // Before changing gallery item
                    beforeClose  : $.noop, // Before closing
                    afterClose   : $.noop  // After closing
                },

                //Current state
                group    : {}, // Selected group
                opts     : {}, // Group options
                previous : null,  // Previous element
                coming   : null,  // Element being loaded
                current  : null,  // Currently loaded element
                isActive : false, // Is activated
                isOpen   : false, // Is currently open
                isOpened : false, // Have been fully opened at least once

                wrap  : null,
                skin  : null,
                outer : null,
                inner : null,

                player : {
                    timer    : null,
                    isActive : false
                },

                // Loaders
                ajaxLoad   : null,
                imgPreload : null,

                // Some collections
                transitions : {},
                helpers     : {},

                /*
                 *	Static methods
                 */

                open: function (group, opts) {
                    if (!group) {
                        return;
                    }

                    if (!$.isPlainObject(opts)) {
                        opts = {};
                    }

                    // Close if already active
                    if (false === F.close(true)) {
                        return;
                    }

                    // Normalize group
                    if (!$.isArray(group)) {
                        group = isQuery(group) ? $(group).get() : [group];
                    }

                    // Recheck if the type of each element is `object` and set content type (image, ajax, etc)
                    $.each(group, function(i, element) {
                        var obj = {},
                            href,
                            title,
                            content,
                            type,
                            rez,
                            hrefParts,
                            selector;

                        if ($.type(element) === "object") {
                            // Check if is DOM element
                            if (element.nodeType) {
                                element = $(element);
                            }

                            if (isQuery(element)) {
                                obj = {
                                    href    : element.data('fancybox-href') || element.attr('href'),
                                    title   : element.data('fancybox-title') || element.attr('title'),
                                    isDom   : true,
                                    element : element
                                };

                                if ($.metadata) {
                                    $.extend(true, obj, element.metadata());
                                }

                            } else {
                                obj = element;
                            }
                        }

                        href  = opts.href  || obj.href || (isString(element) ? element : null);
                        title = opts.title !== undefined ? opts.title : obj.title || '';

                        content = opts.content || obj.content;
                        type    = content ? 'html' : (opts.type  || obj.type);

                        if (!type && obj.isDom) {
                            type = element.data('fancybox-type');

                            if (!type) {
                                rez  = element.prop('class').match(/fancybox\.(\w+)/);
                                type = rez ? rez[1] : null;
                            }
                        }

                        if (isString(href)) {
                            // Try to guess the content type
                            if (!type) {
                                if (F.isImage(href)) {
                                    type = 'image';

                                } else if (F.isSWF(href)) {
                                    type = 'swf';

                                } else if (href.charAt(0) === '#') {
                                    type = 'inline';

                                } else if (isString(element)) {
                                    type    = 'html';
                                    content = element;
                                }
                            }

                            // Split url into two pieces with source url and content selector, e.g,
                            // "/mypage.html #my_id" will load "/mypage.html" and display element having id "my_id"
                            if (type === 'ajax') {
                                hrefParts = href.split(/\s+/, 2);
                                href      = hrefParts.shift();
                                selector  = hrefParts.shift();
                            }
                        }

                        if (!content) {
                            if (type === 'inline') {
                                if (href) {
                                    content = $( isString(href) ? href.replace(/.*(?=#[^\s]+$)/, '') : href ); //strip for ie7

                                } else if (obj.isDom) {
                                    content = element;
                                }

                            } else if (type === 'html') {
                                content = href;

                            } else if (!type && !href && obj.isDom) {
                                type    = 'inline';
                                content = element;
                            }
                        }

                        $.extend(obj, {
                            href     : href,
                            type     : type,
                            content  : content,
                            title    : title,
                            selector : selector
                        });

                        group[ i ] = obj;
                    });

                    // Extend the defaults
                    F.opts = $.extend(true, {}, F.defaults, opts);

                    // All options are merged recursive except keys
                    if (opts.keys !== undefined) {
                        F.opts.keys = opts.keys ? $.extend({}, F.defaults.keys, opts.keys) : false;
                    }

                    F.group = group;

                    return F._start(F.opts.index);
                },

                // Cancel image loading or abort ajax request
                cancel: function () {
                    var coming = F.coming;

                    if (!coming || false === F.trigger('onCancel')) {
                        return;
                    }

                    F.hideLoading();

                    if (F.ajaxLoad) {
                        F.ajaxLoad.abort();
                    }

                    F.ajaxLoad = null;

                    if (F.imgPreload) {
                        F.imgPreload.onload = F.imgPreload.onerror = null;
                    }

                    if (coming.wrap) {
                        coming.wrap.stop(true, true).trigger('onReset').remove();
                    }

                    F.coming = null;

                    // If the first item has been canceled, then clear everything
                    if (!F.current) {
                        F._afterZoomOut( coming );
                    }
                },

                // Start closing animation if is open; remove immediately if opening/closing
                close: function (event) {
                    F.cancel();

                    if (false === F.trigger('beforeClose')) {
                        return;
                    }

                    F.unbindEvents();

                    if (!F.isActive) {
                        return;
                    }

                    if (!F.isOpen || event === true) {
                        $('.fancybox-wrap').stop(true).trigger('onReset').remove();

                        F._afterZoomOut();

                    } else {
                        F.isOpen = F.isOpened = false;
                        F.isClosing = true;

                        $('.fancybox-item, .fancybox-nav').remove();

                        F.wrap.stop(true, true).removeClass('fancybox-opened');

                        F.transitions[ F.current.closeMethod ]();
                    }
                },

                // Manage slideshow:
                //   $.fancybox.play(); - toggle slideshow
                //   $.fancybox.play( true ); - start
                //   $.fancybox.play( false ); - stop
                play: function ( action ) {
                    var clear = function () {
                            clearTimeout(F.player.timer);
                        },
                        set = function () {
                            clear();

                            if (F.current && F.player.isActive) {
                                F.player.timer = setTimeout(F.next, F.current.playSpeed);
                            }
                        },
                        stop = function () {
                            clear();

                            $('body').unbind('.player');

                            F.player.isActive = false;

                            F.trigger('onPlayEnd');
                        },
                        start = function () {
                            if (F.current && (F.current.loop || F.current.index < F.group.length - 1)) {
                                F.player.isActive = true;

                                $('body').bind({
                                    'afterShow.player onUpdate.player'   : set,
                                    'onCancel.player beforeClose.player' : stop,
                                    'beforeLoad.player' : clear
                                });

                                set();

                                F.trigger('onPlayStart');
                            }
                        };

                    if (action === true || (!F.player.isActive && action !== false)) {
                        start();
                    } else {
                        stop();
                    }
                },

                // Navigate to next gallery item
                next: function ( direction ) {
                    var current = F.current;

                    if (current) {
                        if (!isString(direction)) {
                            direction = current.direction.next;
                        }

                        F.jumpto(current.index + 1, direction, 'next');
                    }
                },

                // Navigate to previous gallery item
                prev: function ( direction ) {
                    var current = F.current;

                    if (current) {
                        if (!isString(direction)) {
                            direction = current.direction.prev;
                        }

                        F.jumpto(current.index - 1, direction, 'prev');
                    }
                },

                // Navigate to gallery item by index
                jumpto: function ( index, direction, router ) {
                    var current = F.current;

                    if (!current) {
                        return;
                    }

                    index = getScalar(index);

                    F.direction = direction || current.direction[ (index >= current.index ? 'next' : 'prev') ];
                    F.router    = router || 'jumpto';

                    if (current.loop) {
                        if (index < 0) {
                            index = current.group.length + (index % current.group.length);
                        }

                        index = index % current.group.length;
                    }

                    if (current.group[ index ] !== undefined) {
                        F.cancel();

                        F._start(index);
                    }
                },

                // Center inside viewport and toggle position type to fixed or absolute if needed
                reposition: function (e, onlyAbsolute) {
                    var current = F.current,
                        wrap    = current ? current.wrap : null,
                        pos;

                    if (wrap) {
                        pos = F._getPosition(onlyAbsolute);

                        if (e && e.type === 'scroll') {
                            delete pos.position;

                            wrap.stop(true, true).animate(pos, 200);

                        } else {
                            wrap.css(pos);

                            current.pos = $.extend({}, current.dim, pos);
                        }
                    }
                },

                update: function (e) {
                    var type = (e && e.type),
                        anyway = !type || type === 'orientationchange';

                    if (anyway) {
                        clearTimeout(didUpdate);

                        didUpdate = null;
                    }

                    if (!F.isOpen || didUpdate) {
                        return;
                    }

                    didUpdate = setTimeout(function() {
                        var current = F.current;

                        if (!current || F.isClosing) {
                            return;
                        }

                        F.wrap.removeClass('fancybox-tmp');

                        if (anyway || type === 'load' || (type === 'resize' && current.autoResize)) {
                            F._setDimension();
                        }

                        if (!(type === 'scroll' && current.canShrink)) {
                            F.reposition(e);
                        }

                        F.trigger('onUpdate');

                        didUpdate = null;

                    }, (anyway && !isTouch ? 0 : 300));
                },

                // Shrink content to fit inside viewport or restore if resized
                toggle: function ( action ) {
                    if (F.isOpen) {
                        F.current.fitToView = $.type(action) === "boolean" ? action : !F.current.fitToView;

                        // Help browser to restore document dimensions
                        if (isTouch) {
                            F.wrap.removeAttr('style').addClass('fancybox-tmp');

                            F.trigger('onUpdate');
                        }

                        F.update();
                    }
                },

                hideLoading: function () {
                    D.unbind('.loading');

                    $('#fancybox-loading').remove();
                },

                showLoading: function () {
                    var el, viewport;

                    F.hideLoading();

                    el = $('<div id="fancybox-loading"><div></div></div>').click(F.cancel).appendTo('body');

                    // If user will press the escape-button, the request will be canceled
                    D.bind('keydown.loading', function(e) {
                        if ((e.which || e.keyCode) === 27) {
                            e.preventDefault();

                            F.cancel();
                        }
                    });

                    if (!F.defaults.fixed) {
                        viewport = F.getViewport();

                        el.css({
                            position : 'absolute',
                            top  : (viewport.h * 0.5) + viewport.y,
                            left : (viewport.w * 0.5) + viewport.x
                        });
                    }
                },

                getViewport: function () {
                    var locked = (F.current && F.current.locked) || false,
                        rez    = {
                            x: W.scrollLeft(),
                            y: W.scrollTop()
                        };

                    if (locked) {
                        rez.w = locked[0].clientWidth;
                        rez.h = locked[0].clientHeight;

                    } else {
                        // See http://bugs.jquery.com/ticket/6724
                        rez.w = isTouch && window.innerWidth  ? window.innerWidth  : W.width();
                        rez.h = isTouch && window.innerHeight ? window.innerHeight : W.height();
                    }

                    return rez;
                },

                // Unbind the keyboard / clicking actions
                unbindEvents: function () {
                    if (F.wrap && isQuery(F.wrap)) {
                        F.wrap.unbind('.fb');
                    }

                    D.unbind('.fb');
                    W.unbind('.fb');
                },

                bindEvents: function () {
                    var current = F.current,
                        keys;

                    if (!current) {
                        return;
                    }

                    // Changing document height on iOS devices triggers a 'resize' event,
                    // that can change document height... repeating infinitely
                    W.bind('orientationchange.fb' + (isTouch ? '' : ' resize.fb') + (current.autoCenter && !current.locked ? ' scroll.fb' : ''), F.update);

                    keys = current.keys;

                    if (keys) {
                        D.bind('keydown.fb', function (e) {
                            var code   = e.which || e.keyCode,
                                target = e.target || e.srcElement;

                            // Skip esc key if loading, because showLoading will cancel preloading
                            if (code === 27 && F.coming) {
                                return false;
                            }

                            // Ignore key combinations and key events within form elements
                            if (!e.ctrlKey && !e.altKey && !e.shiftKey && !e.metaKey && !(target && (target.type || $(target).is('[contenteditable]')))) {
                                $.each(keys, function(i, val) {
                                    if (current.group.length > 1 && val[ code ] !== undefined) {
                                        F[ i ]( val[ code ] );

                                        e.preventDefault();
                                        return false;
                                    }

                                    if ($.inArray(code, val) > -1) {
                                        F[ i ] ();

                                        e.preventDefault();
                                        return false;
                                    }
                                });
                            }
                        });
                    }

                    if ($.fn.mousewheel && current.mouseWheel) {
                        F.wrap.bind('mousewheel.fb', function (e, delta, deltaX, deltaY) {
                            var target = e.target || null,
                                parent = $(target),
                                canScroll = false;

                            while (parent.length) {
                                if (canScroll || parent.is('.fancybox-skin') || parent.is('.fancybox-wrap')) {
                                    break;
                                }

                                canScroll = isScrollable( parent[0] );
                                parent    = $(parent).parent();
                            }

                            if (delta !== 0 && !canScroll) {
                                if (F.group.length > 1 && !current.canShrink) {
                                    if (deltaY > 0 || deltaX > 0) {
                                        F.prev( deltaY > 0 ? 'down' : 'left' );

                                    } else if (deltaY < 0 || deltaX < 0) {
                                        F.next( deltaY < 0 ? 'up' : 'right' );
                                    }

                                    e.preventDefault();
                                }
                            }
                        });
                    }
                },

                trigger: function (event, o) {
                    var ret, obj = o || F.coming || F.current;

                    if (!obj) {
                        return;
                    }

                    if ($.isFunction( obj[event] )) {
                        ret = obj[event].apply(obj, Array.prototype.slice.call(arguments, 1));
                    }

                    if (ret === false) {
                        return false;
                    }

                    if (obj.helpers) {
                        $.each(obj.helpers, function (helper, opts) {
                            if (opts && F.helpers[helper] && $.isFunction(F.helpers[helper][event])) {
                                opts = $.extend(true, {}, F.helpers[helper].defaults, opts);

                                F.helpers[helper][event](opts, obj);
                            }
                        });
                    }

                    $.event.trigger(event + '.fb');
                },

                isImage: function (str) {
                    return isString(str) && str.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp)((\?|#).*)?$)/i);
                },

                isSWF: function (str) {
                    return isString(str) && str.match(/\.(swf)((\?|#).*)?$/i);
                },

                _start: function (index) {
                    var coming = {},
                        obj,
                        href,
                        type,
                        margin,
                        padding;

                    index = getScalar( index );
                    obj   = F.group[ index ] || null;

                    if (!obj) {
                        return false;
                    }

                    coming = $.extend(true, {}, F.opts, obj);

                    // Convert margin and padding properties to array - top, right, bottom, left
                    margin  = coming.margin;
                    padding = coming.padding;

                    if ($.type(margin) === 'number') {
                        coming.margin = [margin, margin, margin, margin];
                    }

                    if ($.type(padding) === 'number') {
                        coming.padding = [padding, padding, padding, padding];
                    }

                    // 'modal' propery is just a shortcut
                    if (coming.modal) {
                        $.extend(true, coming, {
                            closeBtn   : false,
                            closeClick : false,
                            nextClick  : false,
                            arrows     : false,
                            mouseWheel : false,
                            keys       : null,
                            helpers: {
                                overlay : {
                                    closeClick : false
                                }
                            }
                        });
                    }

                    // 'autoSize' property is a shortcut, too
                    if (coming.autoSize) {
                        coming.autoWidth = coming.autoHeight = true;
                    }

                    if (coming.width === 'auto') {
                        coming.autoWidth = true;
                    }

                    if (coming.height === 'auto') {
                        coming.autoHeight = true;
                    }

                    /*
                     * Add reference to the group, so it`s possible to access from callbacks, example:
                     * afterLoad : function() {
                     *     this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                     * }
                     */

                    coming.group  = F.group;
                    coming.index  = index;

                    // Give a chance for callback or helpers to update coming item (type, title, etc)
                    F.coming = coming;

                    if (false === F.trigger('beforeLoad')) {
                        F.coming = null;

                        return;
                    }

                    type = coming.type;
                    href = coming.href;

                    if (!type) {
                        F.coming = null;

                        //If we can not determine content type then drop silently or display next/prev item if looping through gallery
                        if (F.current && F.router && F.router !== 'jumpto') {
                            F.current.index = index;

                            return F[ F.router ]( F.direction );
                        }

                        return false;
                    }

                    F.isActive = true;

                    if (type === 'image' || type === 'swf') {
                        coming.autoHeight = coming.autoWidth = false;
                        coming.scrolling  = 'visible';
                    }

                    if (type === 'image') {
                        coming.aspectRatio = true;
                    }

                    if (type === 'iframe' && isTouch) {
                        coming.scrolling = 'scroll';
                    }

                    // Build the neccessary markup
                    coming.wrap = $(coming.tpl.wrap).addClass('fancybox-' + (isTouch ? 'mobile' : 'desktop') + ' fancybox-type-' + type + ' fancybox-tmp ' + coming.wrapCSS).appendTo( coming.parent || 'body' );

                    $.extend(coming, {
                        skin  : $('.fancybox-skin',  coming.wrap),
                        outer : $('.fancybox-outer', coming.wrap),
                        inner : $('.fancybox-inner', coming.wrap)
                    });

                    $.each(["Top", "Right", "Bottom", "Left"], function(i, v) {
                        coming.skin.css('padding' + v, getValue(coming.padding[ i ]));
                    });

                    F.trigger('onReady');

                    // Check before try to load; 'inline' and 'html' types need content, others - href
                    if (type === 'inline' || type === 'html') {
                        if (!coming.content || !coming.content.length) {
                            return F._error( 'content' );
                        }

                    } else if (!href) {
                        return F._error( 'href' );
                    }

                    if (type === 'image') {
                        F._loadImage();

                    } else if (type === 'ajax') {
                        F._loadAjax();

                    } else if (type === 'iframe') {
                        F._loadIframe();

                    } else {
                        F._afterLoad();
                    }
                },

                _error: function ( type ) {
                    $.extend(F.coming, {
                        type       : 'html',
                        autoWidth  : true,
                        autoHeight : true,
                        minWidth   : 0,
                        minHeight  : 0,
                        scrolling  : 'no',
                        hasError   : type,
                        content    : F.coming.tpl.error
                    });

                    F._afterLoad();
                },

                _loadImage: function () {
                    // Reset preload image so it is later possible to check "complete" property
                    var img = F.imgPreload = new Image();

                    img.onload = function () {
                        this.onload = this.onerror = null;

                        F.coming.width  = this.width;
                        F.coming.height = this.height;

                        F._afterLoad();
                    };

                    img.onerror = function () {
                        this.onload = this.onerror = null;

                        F._error( 'image' );
                    };

                    img.src = F.coming.href;

                    if (img.complete !== true) {
                        F.showLoading();
                    }
                },

                _loadAjax: function () {
                    var coming = F.coming;

                    F.showLoading();

                    F.ajaxLoad = $.ajax($.extend({}, coming.ajax, {
                        url: coming.href,
                        error: function (jqXHR, textStatus) {
                            if (F.coming && textStatus !== 'abort') {
                                F._error( 'ajax', jqXHR );

                            } else {
                                F.hideLoading();
                            }
                        },
                        success: function (data, textStatus) {
                            if (textStatus === 'success') {
                                coming.content = data;

                                F._afterLoad();
                            }
                        }
                    }));
                },

                _loadIframe: function() {
                    var coming = F.coming,
                        iframe = $(coming.tpl.iframe.replace(/\{rnd\}/g, new Date().getTime()))
                            .attr('scrolling', isTouch ? 'auto' : coming.iframe.scrolling)
                            .attr('src', coming.href);

                    // This helps IE
                    $(coming.wrap).bind('onReset', function () {
                        try {
                            $(this).find('iframe').hide().attr('src', '//about:blank').end().empty();
                        } catch (e) {}
                    });

                    if (coming.iframe.preload) {
                        F.showLoading();

                        iframe.one('load', function() {
                            $(this).data('ready', 1);

                            // iOS will lose scrolling if we resize
                            if (!isTouch) {
                                $(this).bind('load.fb', F.update);
                            }

                            // Without this trick:
                            //   - iframe won't scroll on iOS devices
                            //   - IE7 sometimes displays empty iframe
                            $(this).parents('.fancybox-wrap').width('100%').removeClass('fancybox-tmp').show();

                            F._afterLoad();
                        });
                    }

                    coming.content = iframe.appendTo( coming.inner );

                    if (!coming.iframe.preload) {
                        F._afterLoad();
                    }
                },

                _preloadImages: function() {
                    var group   = F.group,
                        current = F.current,
                        len     = group.length,
                        cnt     = current.preload ? Math.min(current.preload, len - 1) : 0,
                        item,
                        i;

                    for (i = 1; i <= cnt; i += 1) {
                        item = group[ (current.index + i ) % len ];

                        if (item.type === 'image' && item.href) {
                            new Image().src = item.href;
                        }
                    }
                },

                _afterLoad: function () {
                    var coming   = F.coming,
                        previous = F.current,
                        placeholder = 'fancybox-placeholder',
                        current,
                        content,
                        type,
                        scrolling,
                        href,
                        embed;

                    F.hideLoading();

                    if (!coming || F.isActive === false) {
                        return;
                    }

                    if (false === F.trigger('afterLoad', coming, previous)) {
                        coming.wrap.stop(true).trigger('onReset').remove();

                        F.coming = null;

                        return;
                    }

                    if (previous) {
                        F.trigger('beforeChange', previous);

                        previous.wrap.stop(true).removeClass('fancybox-opened')
                            .find('.fancybox-item, .fancybox-nav')
                            .remove();
                    }

                    F.unbindEvents();

                    current   = coming;
                    content   = coming.content;
                    type      = coming.type;
                    scrolling = coming.scrolling;

                    $.extend(F, {
                        wrap  : current.wrap,
                        skin  : current.skin,
                        outer : current.outer,
                        inner : current.inner,
                        current  : current,
                        previous : previous
                    });

                    href = current.href;

                    switch (type) {
                        case 'inline':
                        case 'ajax':
                        case 'html':
                            if (current.selector) {
                                content = $('<div>').html(content).find(current.selector);

                            } else if (isQuery(content)) {
                                if (!content.data(placeholder)) {
                                    content.data(placeholder, $('<div class="' + placeholder + '"></div>').insertAfter( content ).hide() );
                                }

                                content = content.show().detach();

                                current.wrap.bind('onReset', function () {
                                    if ($(this).find(content).length) {
                                        content.hide().replaceAll( content.data(placeholder) ).data(placeholder, false);
                                    }
                                });
                            }
                            break;

                        case 'image':
                            content = current.tpl.image.replace('{href}', href);
                            break;

                        case 'swf':
                            content = '<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + href + '"></param>';
                            embed   = '';

                            $.each(current.swf, function(name, val) {
                                content += '<param name="' + name + '" value="' + val + '"></param>';
                                embed   += ' ' + name + '="' + val + '"';
                            });

                            content += '<embed src="' + href + '" type="application/x-shockwave-flash" width="100%" height="100%"' + embed + '></embed></object>';
                            break;
                    }

                    if (!(isQuery(content) && content.parent().is(current.inner))) {
                        current.inner.append( content );
                    }

                    // Give a chance for helpers or callbacks to update elements
                    F.trigger('beforeShow');

                    // Set scrolling before calculating dimensions
                    current.inner.css('overflow', scrolling === 'yes' ? 'scroll' : (scrolling === 'no' ? 'hidden' : scrolling));

                    // Set initial dimensions and start position
                    F._setDimension();

                    F.reposition();

                    F.isOpen = false;
                    F.coming = null;

                    F.bindEvents();

                    if (!F.isOpened) {
                        $('.fancybox-wrap').not( current.wrap ).stop(true).trigger('onReset').remove();

                    } else if (previous.prevMethod) {
                        F.transitions[ previous.prevMethod ]();
                    }

                    F.transitions[ F.isOpened ? current.nextMethod : current.openMethod ]();

                    F._preloadImages();
                },

                _setDimension: function () {
                    var viewport   = F.getViewport(),
                        steps      = 0,
                        canShrink  = false,
                        canExpand  = false,
                        wrap       = F.wrap,
                        skin       = F.skin,
                        inner      = F.inner,
                        current    = F.current,
                        width      = current.width,
                        height     = current.height,
                        minWidth   = current.minWidth,
                        minHeight  = current.minHeight,
                        maxWidth   = current.maxWidth,
                        maxHeight  = current.maxHeight,
                        scrolling  = current.scrolling,
                        scrollOut  = current.scrollOutside ? current.scrollbarWidth : 0,
                        margin     = current.margin,
                        wMargin    = getScalar(margin[1] + margin[3]),
                        hMargin    = getScalar(margin[0] + margin[2]),
                        wPadding,
                        hPadding,
                        wSpace,
                        hSpace,
                        origWidth,
                        origHeight,
                        origMaxWidth,
                        origMaxHeight,
                        ratio,
                        width_,
                        height_,
                        maxWidth_,
                        maxHeight_,
                        iframe,
                        body;

                    // Reset dimensions so we could re-check actual size
                    wrap.add(skin).add(inner).width('auto').height('auto').removeClass('fancybox-tmp');

                    wPadding = getScalar(skin.outerWidth(true)  - skin.width());
                    hPadding = getScalar(skin.outerHeight(true) - skin.height());

                    // Any space between content and viewport (margin, padding, border, title)
                    wSpace = wMargin + wPadding;
                    hSpace = hMargin + hPadding;

                    origWidth  = isPercentage(width)  ? (viewport.w - wSpace) * getScalar(width)  / 100 : width;
                    origHeight = isPercentage(height) ? (viewport.h - hSpace) * getScalar(height) / 100 : height;

                    if (current.type === 'iframe') {
                        iframe = current.content;

                        if (current.autoHeight && iframe.data('ready') === 1) {
                            try {
                                if (iframe[0].contentWindow.document.location) {
                                    inner.width( origWidth ).height(9999);

                                    body = iframe.contents().find('body');

                                    if (scrollOut) {
                                        body.css('overflow-x', 'hidden');
                                    }

                                    origHeight = body.height();
                                }

                            } catch (e) {}
                        }

                    } else if (current.autoWidth || current.autoHeight) {
                        inner.addClass( 'fancybox-tmp' );

                        // Set width or height in case we need to calculate only one dimension
                        if (!current.autoWidth) {
                            inner.width( origWidth );
                        }

                        if (!current.autoHeight) {
                            inner.height( origHeight );
                        }

                        if (current.autoWidth) {
                            origWidth = inner.width();
                        }

                        if (current.autoHeight) {
                            origHeight = inner.height();
                        }

                        inner.removeClass( 'fancybox-tmp' );
                    }

                    width  = getScalar( origWidth );
                    height = getScalar( origHeight );

                    ratio  = origWidth / origHeight;

                    // Calculations for the content
                    minWidth  = getScalar(isPercentage(minWidth) ? getScalar(minWidth, 'w') - wSpace : minWidth);
                    maxWidth  = getScalar(isPercentage(maxWidth) ? getScalar(maxWidth, 'w') - wSpace : maxWidth);

                    minHeight = getScalar(isPercentage(minHeight) ? getScalar(minHeight, 'h') - hSpace : minHeight);
                    maxHeight = getScalar(isPercentage(maxHeight) ? getScalar(maxHeight, 'h') - hSpace : maxHeight);

                    // These will be used to determine if wrap can fit in the viewport
                    origMaxWidth  = maxWidth;
                    origMaxHeight = maxHeight;

                    if (current.fitToView) {
                        maxWidth  = Math.min(viewport.w - wSpace, maxWidth);
                        maxHeight = Math.min(viewport.h - hSpace, maxHeight);
                    }

                    maxWidth_  = viewport.w - wMargin;
                    maxHeight_ = viewport.h - hMargin;

                    if (current.aspectRatio) {
                        if (width > maxWidth) {
                            width  = maxWidth;
                            height = getScalar(width / ratio);
                        }

                        if (height > maxHeight) {
                            height = maxHeight;
                            width  = getScalar(height * ratio);
                        }

                        if (width < minWidth) {
                            width  = minWidth;
                            height = getScalar(width / ratio);
                        }

                        if (height < minHeight) {
                            height = minHeight;
                            width  = getScalar(height * ratio);
                        }

                    } else {
                        width = Math.max(minWidth, Math.min(width, maxWidth));

                        if (current.autoHeight && current.type !== 'iframe') {
                            inner.width( width );

                            height = inner.height();
                        }

                        height = Math.max(minHeight, Math.min(height, maxHeight));
                    }

                    // Try to fit inside viewport (including the title)
                    if (current.fitToView) {
                        inner.width( width ).height( height );

                        wrap.width( width + wPadding );

                        // Real wrap dimensions
                        width_  = wrap.width();
                        height_ = wrap.height();

                        if (current.aspectRatio) {
                            while ((width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight) {
                                if (steps++ > 19) {
                                    break;
                                }

                                height = Math.max(minHeight, Math.min(maxHeight, height - 10));
                                width  = getScalar(height * ratio);

                                if (width < minWidth) {
                                    width  = minWidth;
                                    height = getScalar(width / ratio);
                                }

                                if (width > maxWidth) {
                                    width  = maxWidth;
                                    height = getScalar(width / ratio);
                                }

                                inner.width( width ).height( height );

                                wrap.width( width + wPadding );

                                width_  = wrap.width();
                                height_ = wrap.height();
                            }

                        } else {
                            width  = Math.max(minWidth,  Math.min(width,  width  - (width_  - maxWidth_)));
                            height = Math.max(minHeight, Math.min(height, height - (height_ - maxHeight_)));
                        }
                    }

                    if (scrollOut && scrolling === 'auto' && height < origHeight && (width + wPadding + scrollOut) < maxWidth_) {
                        width += scrollOut;
                    }

                    inner.width( width ).height( height );

                    wrap.width( width + wPadding );

                    width_  = wrap.width();
                    height_ = wrap.height();

                    canShrink = (width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight;
                    canExpand = current.aspectRatio ? (width < origMaxWidth && height < origMaxHeight && width < origWidth && height < origHeight) : ((width < origMaxWidth || height < origMaxHeight) && (width < origWidth || height < origHeight));

                    $.extend(current, {
                        dim : {
                            width	: getValue( width_ ),
                            height	: getValue( height_ )
                        },
                        origWidth  : origWidth,
                        origHeight : origHeight,
                        canShrink  : canShrink,
                        canExpand  : canExpand,
                        wPadding   : wPadding,
                        hPadding   : hPadding,
                        wrapSpace  : height_ - skin.outerHeight(true),
                        skinSpace  : skin.height() - height
                    });

                    if (!iframe && current.autoHeight && height > minHeight && height < maxHeight && !canExpand) {
                        inner.height('auto');
                    }
                },

                _getPosition: function (onlyAbsolute) {
                    var current  = F.current,
                        viewport = F.getViewport(),
                        margin   = current.margin,
                        width    = F.wrap.width()  + margin[1] + margin[3],
                        height   = F.wrap.height() + margin[0] + margin[2],
                        rez      = {
                            position: 'absolute',
                            top  : margin[0],
                            left : margin[3]
                        };

                    if (current.autoCenter && current.fixed && !onlyAbsolute && height <= viewport.h && width <= viewport.w) {
                        rez.position = 'fixed';

                    } else if (!current.locked) {
                        rez.top  += viewport.y;
                        rez.left += viewport.x;
                    }

                    rez.top  = getValue(Math.max(rez.top,  rez.top  + ((viewport.h - height) * current.topRatio)));
                    rez.left = getValue(Math.max(rez.left, rez.left + ((viewport.w - width)  * current.leftRatio)));

                    return rez;
                },

                _afterZoomIn: function () {
                    var current = F.current;

                    if (!current) {
                        return;
                    }

                    F.isOpen = F.isOpened = true;

                    F.wrap.css('overflow', 'visible').addClass('fancybox-opened');

                    F.update();

                    // Assign a click event
                    if ( current.closeClick || (current.nextClick && F.group.length > 1) ) {
                        F.inner.css('cursor', 'pointer').bind('click.fb', function(e) {
                            if (!$(e.target).is('a') && !$(e.target).parent().is('a')) {
                                e.preventDefault();

                                F[ current.closeClick ? 'close' : 'next' ]();
                            }
                        });
                    }

                    // Create a close button
                    if (current.closeBtn) {
                        $(current.tpl.closeBtn).appendTo(F.skin).bind( isTouch ? 'touchstart.fb' : 'click.fb', function(e) {
                            e.preventDefault();

                            F.close();
                        });
                    }

                    // Create navigation arrows
                    if (current.arrows && F.group.length > 1) {
                        if (current.loop || current.index > 0) {
                            $(current.tpl.prev).appendTo(F.outer).bind('click.fb', F.prev);
                        }

                        if (current.loop || current.index < F.group.length - 1) {
                            $(current.tpl.next).appendTo(F.outer).bind('click.fb', F.next);
                        }
                    }

                    F.trigger('afterShow');

                    // Stop the slideshow if this is the last item
                    if (!current.loop && current.index === current.group.length - 1) {
                        F.play( false );

                    } else if (F.opts.autoPlay && !F.player.isActive) {
                        F.opts.autoPlay = false;

                        F.play();
                    }
                },

                _afterZoomOut: function ( obj ) {
                    obj = obj || F.current;

                    $('.fancybox-wrap').trigger('onReset').remove();

                    $.extend(F, {
                        group  : {},
                        opts   : {},
                        router : false,
                        current   : null,
                        isActive  : false,
                        isOpened  : false,
                        isOpen    : false,
                        isClosing : false,
                        wrap   : null,
                        skin   : null,
                        outer  : null,
                        inner  : null
                    });

                    F.trigger('afterClose', obj);
                }
            });

            /*
             *	Default transitions
             */

            F.transitions = {
                getOrigPosition: function () {
                    var current  = F.current,
                        element  = current.element,
                        orig     = current.orig,
                        pos      = {},
                        width    = 50,
                        height   = 50,
                        hPadding = current.hPadding,
                        wPadding = current.wPadding,
                        viewport = F.getViewport();

                    if (!orig && current.isDom && element.is(':visible')) {
                        orig = element.find('img:first');

                        if (!orig.length) {
                            orig = element;
                        }
                    }

                    if (isQuery(orig)) {
                        pos = orig.offset();

                        if (orig.is('img')) {
                            width  = orig.outerWidth();
                            height = orig.outerHeight();
                        }

                    } else {
                        pos.top  = viewport.y + (viewport.h - height) * current.topRatio;
                        pos.left = viewport.x + (viewport.w - width)  * current.leftRatio;
                    }

                    if (F.wrap.css('position') === 'fixed' || current.locked) {
                        pos.top  -= viewport.y;
                        pos.left -= viewport.x;
                    }

                    pos = {
                        top     : getValue(pos.top  - hPadding * current.topRatio),
                        left    : getValue(pos.left - wPadding * current.leftRatio),
                        width   : getValue(width  + wPadding),
                        height  : getValue(height + hPadding)
                    };

                    return pos;
                },

                step: function (now, fx) {
                    var ratio,
                        padding,
                        value,
                        prop       = fx.prop,
                        current    = F.current,
                        wrapSpace  = current.wrapSpace,
                        skinSpace  = current.skinSpace;

                    if (prop === 'width' || prop === 'height') {
                        ratio = fx.end === fx.start ? 1 : (now - fx.start) / (fx.end - fx.start);

                        if (F.isClosing) {
                            ratio = 1 - ratio;
                        }

                        padding = prop === 'width' ? current.wPadding : current.hPadding;
                        value   = now - padding;

                        F.skin[ prop ](  getScalar( prop === 'width' ?  value : value - (wrapSpace * ratio) ) );
                        F.inner[ prop ]( getScalar( prop === 'width' ?  value : value - (wrapSpace * ratio) - (skinSpace * ratio) ) );
                    }
                },

                zoomIn: function () {
                    var current  = F.current,
                        startPos = current.pos,
                        effect   = current.openEffect,
                        elastic  = effect === 'elastic',
                        endPos   = $.extend({opacity : 1}, startPos);

                    // Remove "position" property that breaks older IE
                    delete endPos.position;

                    if (elastic) {
                        startPos = this.getOrigPosition();

                        if (current.openOpacity) {
                            startPos.opacity = 0.1;
                        }

                    } else if (effect === 'fade') {
                        startPos.opacity = 0.1;
                    }

                    F.wrap.css(startPos).animate(endPos, {
                        duration : effect === 'none' ? 0 : current.openSpeed,
                        easing   : current.openEasing,
                        step     : elastic ? this.step : null,
                        complete : F._afterZoomIn
                    });
                },

                zoomOut: function () {
                    var current  = F.current,
                        effect   = current.closeEffect,
                        elastic  = effect === 'elastic',
                        endPos   = {opacity : 0.1};

                    if (elastic) {
                        endPos = this.getOrigPosition();

                        if (current.closeOpacity) {
                            endPos.opacity = 0.1;
                        }
                    }

                    F.wrap.animate(endPos, {
                        duration : effect === 'none' ? 0 : current.closeSpeed,
                        easing   : current.closeEasing,
                        step     : elastic ? this.step : null,
                        complete : F._afterZoomOut
                    });
                },

                changeIn: function () {
                    var current   = F.current,
                        effect    = current.nextEffect,
                        startPos  = current.pos,
                        endPos    = { opacity : 1 },
                        direction = F.direction,
                        distance  = 200,
                        field;

                    startPos.opacity = 0.1;

                    if (effect === 'elastic') {
                        field = direction === 'down' || direction === 'up' ? 'top' : 'left';

                        if (direction === 'down' || direction === 'right') {
                            startPos[ field ] = getValue(getScalar(startPos[ field ]) - distance);
                            endPos[ field ]   = '+=' + distance + 'px';

                        } else {
                            startPos[ field ] = getValue(getScalar(startPos[ field ]) + distance);
                            endPos[ field ]   = '-=' + distance + 'px';
                        }
                    }

                    // Workaround for http://bugs.jquery.com/ticket/12273
                    if (effect === 'none') {
                        F._afterZoomIn();

                    } else {
                        F.wrap.css(startPos).animate(endPos, {
                            duration : current.nextSpeed,
                            easing   : current.nextEasing,
                            complete : function() {
                                // This helps FireFox to properly render the box
                                setTimeout(F._afterZoomIn, 20);
                            }
                        });
                    }
                },

                changeOut: function () {
                    var previous  = F.previous,
                        effect    = previous.prevEffect,
                        endPos    = { opacity : 0.1 },
                        direction = F.direction,
                        distance  = 200;

                    if (effect === 'elastic') {
                        endPos[ direction === 'down' || direction === 'up' ? 'top' : 'left' ] = ( direction === 'up' || direction === 'left' ? '-' : '+' ) + '=' + distance + 'px';
                    }

                    previous.wrap.animate(endPos, {
                        duration : effect === 'none' ? 0 : previous.prevSpeed,
                        easing   : previous.prevEasing,
                        complete : function () {
                            $(this).trigger('onReset').remove();
                        }
                    });
                }
            };

            /*
             *	Overlay helper
             */

            F.helpers.overlay = {
                defaults : {
                    closeClick : true,  // if true, fancyBox will be closed when user clicks on the overlay
                    speedOut   : 200,   // duration of fadeOut animation
                    showEarly  : true,  // indicates if should be opened immediately or wait until the content is ready
                    css        : {},    // custom CSS properties
                    locked     : !isTouch,  // if true, the content will be locked into overlay
                    fixed      : true   // if false, the overlay CSS position property will not be set to "fixed"
                },

                overlay : null,   // current handle
                fixed   : false,  // indicates if the overlay has position "fixed"

                // Public methods
                create : function(opts) {
                    opts = $.extend({}, this.defaults, opts);

                    if (this.overlay) {
                        this.close();
                    }

                    this.overlay = $('<div class="fancybox-overlay"></div>').appendTo( 'body' );
                    this.fixed   = false;

                    if (opts.fixed && F.defaults.fixed) {
                        this.overlay.addClass('fancybox-overlay-fixed');

                        this.fixed = true;
                    }
                },

                open : function(opts) {
                    var that = this;

                    opts = $.extend({}, this.defaults, opts);

                    if (this.overlay) {
                        this.overlay.unbind('.overlay').width('auto').height('auto');

                    } else {
                        this.create(opts);
                    }

                    if (!this.fixed) {
                        W.bind('resize.overlay', $.proxy( this.update, this) );

                        this.update();
                    }

                    if (opts.closeClick) {
                        this.overlay.bind('click.overlay', function(e) {
                            if ($(e.target).hasClass('fancybox-overlay')) {
                                if (F.isActive) {
                                    F.close();
                                } else {
                                    that.close();
                                }
                            }
                        });
                    }

                    this.overlay.css( opts.css ).show();
                },

                close : function() {
                    $('.fancybox-overlay').remove();

                    W.unbind('resize.overlay');

                    this.overlay = null;

                    if (this.margin !== false) {
                        $('body').css('margin-right', this.margin);

                        this.margin = false;
                    }

                    if (this.el) {
                        this.el.removeClass('fancybox-lock');
                    }
                },

                // Private, callbacks

                update : function () {
                    var width = '100%', offsetWidth;

                    // Reset width/height so it will not mess
                    this.overlay.width(width).height('100%');

                    // jQuery does not return reliable result for IE
                    if ($.browser.msie) {
                        offsetWidth = Math.max(document.documentElement.offsetWidth, document.body.offsetWidth);

                        if (D.width() > offsetWidth) {
                            width = D.width();
                        }

                    } else if (D.width() > W.width()) {
                        width = D.width();
                    }

                    this.overlay.width(width).height(D.height());
                },

                // This is where we can manipulate DOM, because later it would cause iframes to reload
                onReady : function (opts, obj) {
                    $('.fancybox-overlay').stop(true, true);

                    if (!this.overlay) {
                        this.margin = D.height() > W.height() || $('body').css('overflow-y') === 'scroll' ? $('body').css('margin-right') : false;
                        this.el     = document.all && !document.querySelector ? $('html') : $('body');

                        this.create(opts);
                    }

                    if (opts.locked && this.fixed) {
                        obj.locked = this.overlay.append( obj.wrap );
                        obj.fixed  = false;
                    }

                    if (opts.showEarly === true) {
                        this.beforeShow.apply(this, arguments);
                    }
                },

                beforeShow : function(opts, obj) {
                    if (obj.locked) {
                        this.el.addClass('fancybox-lock');

                        if (this.margin !== false) {
                            $('body').css('margin-right', getScalar( this.margin ) + obj.scrollbarWidth);
                        }
                    }

                    this.open(opts);
                },

                onUpdate : function() {
                    if (!this.fixed) {
                        this.update();
                    }
                },

                afterClose: function (opts) {
                    // Remove overlay if exists and fancyBox is not opening
                    // (e.g., it is not being open using afterClose callback)
                    if (this.overlay && !F.isActive) {
                        this.overlay.fadeOut(opts.speedOut, $.proxy( this.close, this ));
                    }
                }
            };

            /*
             *	Title helper
             */

            F.helpers.title = {
                defaults : {
                    type     : 'float', // 'float', 'inside', 'outside' or 'over',
                    position : 'bottom' // 'top' or 'bottom'
                },

                beforeShow: function (opts) {
                    var current = F.current,
                        text    = current.title,
                        type    = opts.type,
                        title,
                        target;

                    if ($.isFunction(text)) {
                        text = text.call(current.element, current);
                    }

                    if (!isString(text) || $.trim(text) === '') {
                        return;
                    }

                    title = $('<div class="fancybox-title fancybox-title-' + type + '-wrap">' + text + '</div>');

                    switch (type) {
                        case 'inside':
                            target = F.skin;
                            break;

                        case 'outside':
                            target = F.wrap;
                            break;

                        case 'over':
                            target = F.inner;
                            break;

                        default: // 'float'
                            target = F.skin;

                            title.appendTo('body');

                            if ($.browser.msie) {
                                title.width( title.width() );
                            }

                            title.wrapInner('<span class="child"></span>');

                            //Increase bottom margin so this title will also fit into viewport
                            F.current.margin[2] += Math.abs( getScalar(title.css('margin-bottom')) );
                            break;
                    }

                    title[ (opts.position === 'top' ? 'prependTo'  : 'appendTo') ](target);
                }
            };

            // jQuery plugin initialization
            $.fn.fancybox = function (options) {
                var index,
                    that     = $(this),
                    selector = this.selector || '',
                    run      = function(e) {
                        var what = $(this).blur(), idx = index, relType, relVal;

                        if (!(e.ctrlKey || e.altKey || e.shiftKey || e.metaKey) && !what.is('.fancybox-wrap')) {
                            relType = options.groupAttr || 'rel';
                            relVal  = what.attr(relType);

                            if (!relVal) {
                                relType = 'rel';
                                relVal  = what.get(0)[ relType ];
                            }

                            if (relVal && relVal !== '' && relVal !== 'nofollow') {
                                what = selector.length ? $(selector) : that;
                                what = what.filter('[' + relType + '="' + relVal + '"]');
                                idx  = what.index(this);
                            }

                            options.index = idx;

                            // Stop an event from bubbling if everything is fine
                            if (F.open(what, options) !== false) {
                                e.preventDefault();
                            }
                        }
                    };

                options = options || {};
                index   = options.index || 0;

                if (!selector || options.live === false) {
                    that.unbind('click.fb-start').bind('click.fb-start', run);

                } else {
                    D.undelegate(selector, 'click.fb-start').delegate(selector + ":not('.fancybox-item, .fancybox-nav')", 'click.fb-start', run);
                }

                this.filter('[data-fancybox-start=1]').trigger('click');

                return this;
            };

            // Tests that need a body at doc ready
            D.ready(function() {
                if ( $.scrollbarWidth === undefined ) {
                    // http://benalman.com/projects/jquery-misc-plugins/#scrollbarwidth
                    $.scrollbarWidth = function() {
                        var parent = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body'),
                            child  = parent.children(),
                            width  = child.innerWidth() - child.height( 99 ).innerWidth();

                        parent.remove();

                        return width;
                    };
                }

                if ( $.support.fixedPosition === undefined ) {
                    $.support.fixedPosition = (function() {
                        var elem  = $('<div style="position:fixed;top:20px;"></div>').appendTo('body'),
                            fixed = ( elem[0].offsetTop === 20 || elem[0].offsetTop === 15 );

                        elem.remove();

                        return fixed;
                    }());
                }

                $.extend(F.defaults, {
                    scrollbarWidth : $.scrollbarWidth(),
                    fixed  : $.support.fixedPosition,
                    parent : $('body')
                });
            });

        }(window, document, jQuery));
    </script>
    <script type="text/javascript">
        /**
         * Created by Dominik on 1.3.2016.
         */
        (function($){$.fn.snow=function(options){var $flake=$('<div id="flake" />').css({'position':'absolute','top':'-50px'}).html('&#10052;'),documentHeight=$(document).height(),documentWidth=$(document).width(),defaults={minSize:0,maxSize:0,newOn:500,flakeColor:"#FFFFFF"},options=$.extend({},defaults,options);var interval=setInterval(function(){var startPositionLeft=Math.random()*documentWidth-100,startOpacity=0.5+Math.random(),sizeFlake=options.minSize+Math.random()*options.maxSize,endPositionTop=documentHeight-40,endPositionLeft=startPositionLeft-100+Math.random()*200,durationFall=documentHeight*10+Math.random()*5000;$flake.clone().appendTo('body').css({left:startPositionLeft,opacity:startOpacity,'font-size':sizeFlake,color:options.flakeColor}).animate({top:endPositionTop,left:endPositionLeft,opacity:0.2},durationFall,'linear',function(){$(this).remove()});},options.newOn);};})(jQuery);
    </script>
    <script type="text/javascript">
        /**
         * Created by Dominik on 1.3.2016.
         */
        $(function(){

            function heads(){
                $.fn.snow();
            }


            function event_handler() {
                $("body").keydown(function (e) {
                    var char = e.keyCode;
                    if (char == 68) {
                        window.location.href = "/dont-eat-yellow-snow";
                    }
                    if(char == 99){
                        heads();
                    }
                });
            }

            event_handler();

        });
    </script>



</head>

<body>

<!--********************************************* Main wrapper Start *********************************************-->
<div id="footer_image">
    <div id="main_wrapper">

        <!--********************************************* Logo Start *********************************************-->
        <div id="logo"> <a href="<?= $root_url ?>"><img alt="alt_example" src="images/logo.png"  /></a>
            <div id="social_ctn">

                <a class="social_t"><img alt="alt_example" src="images/social_tleft.png" /></a>

                <a href="#" id="facebook"><img alt="alt_example" src="images/blank.gif" width="100%"/></a>
                <a href="#" id="google_plus"><img alt="alt_example" src="images/blank.gif" width="100%"/></a>
                <a href="#" id="you_tube"><img alt="alt_example" src="images/blank.gif" width="100%" /></a>

                <a class="social_t" ><img alt="alt_example" src="images/social_tright.png" /></a>

            </div>

        </div>
        <!--********************************************* Logo end *********************************************-->

        <!--********************************************* Main_in Start *********************************************-->
        <div id="main_in">

            <!--********************************************* Mainmenu Start *********************************************-->
            <div id="menu_wrapper">
                <div id="menu_left"></div>
                <ul id="menu">
                    <li><a href="<?= $root_url ?>"><i class="fa fa-home"></i>Domů</a></li>
                    <li><a href="<?= $root_url ?>/novinky"><i class="fa fa-newspaper-o"></i>Novinky</a></li>
                    <li><a href="<?= $root_url ?>/jak-se-pripojit"><i class="fa fa-plug"></i>Jak se připojit?</a></li>
                    <li><a href="<?= $root_url ?>/podminky-sluzby"><i class="fa fa-exclamation-triangle"></i>Podmínky služby</a></li>
                    <li><a href="<?= $root_url ?>/kontakt"><i class="fa fa-envelope"></i>Kontakt</a></li>
                    <li><a href="<?= $root_url ?>/o-nas"><i class="fa fa-pencil"></i>O nás</a></li>
                    <li><a href="<?= $root_url ?>/donate"><i class="fa fa-money"></i> Donate</a></li>
                </ul>
                <a href="#" id="pull">Menu</a>
                <div id="menu_right"></div>
                <div class="clear"></div>
            </div>

            <!--********************************************* Mainmenu end *********************************************-->


            <?php
            #remove the directory path we don't want
            $request  = str_replace("local.chochrun.eu/", "", $_SERVER['REQUEST_URI']);

            #split the path by '/'
            $params = explode("/", $request);
            $page = $params[1];
            $file = $page.".php";
            if(file_exists($file)){
                include $file;
            }else{
                include "domu.php";
            }

            ?>


        </div>
        <!--********************************************* Main_in end *********************************************-->

        <a id="cop_text" href="#">Webserver: <?= apache_get_version() ?> | Verze PHP: <?= phpversion(); ?></a>
    </div>
</div>
<!--********************************************* Main wrapper end *********************************************-->

<!--******* Javascript Code for the Hot news carousel *******-->
<script type="text/javascript">
    $(document).ready(function() {

        // Using default configuration
        $("#sd").carouFredSel();

        // Using custom configuration
        $("#hot_news_box").carouFredSel({
            items				: 3,
            direction			: "right",
            prev: '#prev',
            next: '#next',
            scroll : {
                items			: 1,
                height			: 250,
                easing			: "quadratic",
                duration		: 2000,
                pauseOnHover	: true
            }
        });
    })
</script>


<!--******* Javascript Code for the Main banner *******-->
<script type="text/javascript">
    $(function() {

        $('#da-slider').cslider({
            autoplay	: true,
            bgincrement	: 450,
            interval	: 7000
        });

    });
</script>

<!--******* Javascript Code for the image shadowbox *******-->
<script type="text/javascript">
    $(document).ready(function() {
        /*
         *  Simple image gallery. Uses default settings
         */

        $('.shadowbox').fancybox();
    });
</script>

<!--******* Javascript Code for the menu *******-->

<script type="text/javascript">
    $(document).ready(function() {
        $('#menu li').bind('mouseover', openSubMenu);
        $('#menu > li').bind('mouseout', closeSubMenu);

        function openSubMenu() {
            $(this).find('ul').css('visibility', 'visible');
        };

        function closeSubMenu() {
            $(this).find('ul').css('visibility', 'hidden');
        };
    });
</script>

<script type="text/javascript">
    $(function() {
        var pull    = $('#pull');
        menu 		= $('ul#menu');

        $(pull).on('click', function(e) {
            e.preventDefault();
            menu.slideToggle();
        });

        $(window).resize(function(){
            var w = $(window).width();
            if(w > 767 && $('ul#menu').css('visibility', 'hidden')) {
                $('ul#menu').removeAttr('style');
            };
            var menu = $('#menu_wrapper').width();
            $('#pull').width(menu - 20);
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        var menu = $('#menu_wrapper').width();
        $('#pull').width(menu - 20);
    });
</script>
</body>
</html>
