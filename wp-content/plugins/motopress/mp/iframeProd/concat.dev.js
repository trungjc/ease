steal('jquery/controller', 'jquery/view/ejs')
    .then('mp/grid/views/init.ejs' + parent.motopress.pluginVersionParam, function($) {
    /**
    * @class MP.Grid
    */
    $.Controller('MP.Grid',
    /** @Static */
    {
        myThis: null
        //element: null,

        /*
        setHeight: function(height) {
            MP.Grid.element.css('height', height);
        }
        */
    },
    /** @Prototype */
    {
        columnWidth: null,
        marginWidth: null,
        columnWithMargin: null,
        columns: 12,

        init: function() {
            MP.Grid.myThis = this;

            this.element.html('//mp/grid/views/init.ejs' + parent.motopress.pluginVersionParam, {
                columns: this.columns
            });

            this.setSize();

            $(window).on('resize', this.proxy('setSize'));
        },

        setSize: function() {
            var column = this.element.find('.span1:first');
            this.columnWidth = column.width();
            this.marginWidth = parseInt(column.css('margin-left'));
            this.columnWithMargin = this.columnWidth + this.marginWidth;
            //MP.Grid.element = this.element;
        }
    })
});
steal( 'jquery/class', function($) {
   /**
    * @class MP.LeftMenu
    */
    $.Class('MP.LeftMenu',
    /** @Static */
    {
        myThis: null
    },
    /** @Prototype */
    {
        leftMenu: $('<div />', {
            'class': 'motopress-left-menu motopress-default',
            'id': 'motopress-left-menu'
        }),

        blocks: {
            Layout: [
                //{name: 'Wrapper', type: 'wrapper', image:'../wp-content/plugins/motopress/images/static-icon.png', motopressNew: '1'},
                {name: localStorage.getItem('static'), type: 'static'},
                {name: localStorage.getItem('content'), type: 'loop'},
                {name: localStorage.getItem('sidebar'), type: 'dynamic-sidebar'}
            ]
            /*
            Content: [
                {name: 'Text', id: 'text'},
                {name: 'Image', id: 'image'}
            ]
            */
        },

        setup: function() {
            for(var group in this.blocks) {
                var div = $('<div />', {
                    'class' : 'motopress-left-menu-inner motopress-default'
                });
                var divHeader = $('<div />', {
                    'class' : 'motopress-left-menu-header motopress-default motopress-default-text'
                });
                var spanHeaderText = $('<span />', {
                   'class' : 'motopress-left-menu-header-text motopress-default motopress-default-text',
                    'text' : localStorage.getItem('addBlock')
                }).appendTo(divHeader);
                var spanHeaderPlus = $('<span />', {
                    'class' : 'motopress-left-menu-header-plus motopress-default motopress-default-text',
                    'text' : '+'
                }).appendTo(divHeader);
                divHeader.prependTo(div);
                var marker = $('<div />', {
                    'class': 'motopress-left-menu-icon motopress-new-block-marker-icon motopress-default'
                });
                var text = $('<span />', {
                    'class': 'motopress-new-block-text motopress-default motopress-default-text'
                });
                for (var i = 0; i < this.blocks[group].length; i++) {
                    var block = $('<div />', {
                        'class': 'motopress-new-block motopress-default-drag motopress-default-text',
                        'data-type': this.blocks[group][i].type,
                        'data-motopress-new': this.blocks[group][i].motopressNew
                    });
                    /*
                    if (this.blocks[group][i].type == 'loop') {
                        var page = parent.MP.Navbar.myThis.element.find('option:selected');
                        var loopFile = page.attr('data-motopress-loop-file');
                        var entityType = page.attr('data-motopress-entity-type');
                        var entityId = page.attr('data-motopress-entity-id');

                        block.attr({
                            'data-motopress-loop-file': loopFile,
                            'data-motopress-entity-type': entityType,
                            'data-motopress-entity-id': entityId
                        });
                    }
                    */

                    text.text(this.blocks[group][i].name);
                    var icon = $('<div />', {
                        'class': 'motopress-left-menu-icon motopress-new-block-'+this.blocks[group][i].type+'-icon motopress-default'
                    });

                    block.append(marker.clone(), text.clone(), icon).appendTo(div);
                }
                var infoBlock = $('<div />', {
                    'class' : 'motopress-label-tips motopress-default motopress-default-text motopress-hide',
                    'text' : localStorage.getItem('addBlockTip')
                });
                infoBlock.appendTo(div);
            }
            this.leftMenu.append(div).prependTo('body');
        },

        init: function() {
            MP.LeftMenu.myThis = this;

            this.leftMenu.hover(
                function() {
                    $(this).stop(true, false).animate({left: '0px'});
                    $(this).find('.motopress-label-tips').removeClass('motopress-hide').addClass('motopress-show');
                },
                function() {
                    $(this).stop(true, false).animate({left: '-114px'});
                    $(this).find('.motopress-label-tips').removeClass('motopress-show').addClass('motopress-hide');
                }
            );
        }
    })
});
steal('jquery/class')
    .then(
    'mp/resizer/resizer.js' + parent.motopress.pluginVersionParam,
    'mp/tools/tools.js' + parent.motopress.pluginVersionParam,
    function($) {
   /**
    * @class MP.DragDrop
    */
    $.Class('MP.DragDrop',
    /** @Static */
    {
        myThis: null,

        getSpanClass: function(classes) {
            var expr = new RegExp('^span\\d{1,2}$', 'i');
            var spanClass = null;
            for(var i = 0; i < classes.length; i++) {
                if (expr.test(classes[i])) {
                    spanClass = classes[i];
                }
            }
            return spanClass;
        },

        getSpanNumber: function(spanClass) {
            var exprNumber = new RegExp('\\d{1,2}', 'i');
            return parseInt(spanClass.match(exprNumber));
        },

        ruc: new Array('visible-phone', 'visible-tablet', 'visible-desktop', 'hidden-phone', 'hidden-tablet', 'hidden-desktop')
    },
    /** @Prototype */
    {
        blockContent: $('<div />', {
            'class': 'motopress-block-content'
        }),

        handleTopIn: $('<div />', {
            'class': 'motopress-handle-top-in',
            'data-motopress-position': 'top-in'
        }),
        handleBottomIn: $('<div />', {
            'class': 'motopress-handle-bottom-in',
            'data-motopress-position': 'bottom-in'
        }),
        handleLeftOut: $('<div />', {
            'class': 'motopress-handle-left-out',
            'data-motopress-position': 'left-out'
        }),
        handleRightOut: $('<div />', {
            'class': 'motopress-handle-right-out',
            'data-motopress-position': 'right-out'
        }),
        handleLeftIn: $('<div />', {
            'class': 'motopress-handle-left-in',
            'data-motopress-position': 'left-in'
        }),
        handleRightIn: $('<div />', {
            'class': 'motopress-handle-right-in',
            'data-motopress-position': 'right-in'
        }),

        overlay: $('<div />', {
            'class': 'motopress-overlay'
        }),

        dragHandle: $('<div />', {
            'class': 'motopress-drag-handle'
        }),

        helper: $('<div />', {
            'class': 'motopress-helper'
        }),

        handleMiddleOut: $('<div />', {
            'class': 'motopress-handle-middle-out',
            'data-motopress-position': 'middle-out'
        }),

        handleMiddleIn: $('<div />', {
            'class': 'motopress-handle-middle-in',
            'data-motopress-position': 'middle-in'
        }),

        emptySpanOverlay: $('<div />', {
            'class': 'motopress-overlay'
        }),

        emptySpanHelper: $('<div />', {
            'class': 'motopress-helper'
        }),

        handleWrapper: $('<div />', {
            'class': 'motopress-handle-wrapper'
        }),

        wrapperHelperContainer: $('<div />', {
            'class': 'motopress-wrapper-helper-container'
        }),

        handleWrapperLeft: $('<div />', {
            'class': 'motopress-handle-wrapper-left',
            'data-motopress-position': 'wrapper-left'
        }),
        handleWrapperRight: $('<div />', {
            'class': 'motopress-handle-wrapper-right',
            'data-motopress-position': 'wrapper-right'
        }),

        wrapperHelper: $('<div />', {
            'class': 'motopress-wrapper-helper'
        }),

        wrapperHelperResizer: $('<div />', {
            'class': 'motopress-helper'
        }),

        wrapperHighlight: $('<div />', {
            'class': 'motopress-wrapper-highlight'
        }),

        wrapperId: 0,

        //layout wrapper helpers
        handleLayoutWrapperLeftOut: $('<div />', {
            'class': 'motopress-handle-layout-wrapper-left-out',
            'data-motopress-position': 'left-out'
        }),
        handleLayoutWrapperRightOut: $('<div />', {
            'class': 'motopress-handle-layout-wrapper-right-out',
            'data-motopress-position': 'right-out'
        }),
        layoutWrapperOverlay: $('<div />', {
            'class': 'motopress-layout-wrapper-overlay'
        }),
        layoutWrapperHelper: $('<div />', {
            'class': 'motopress-helper'
        }),
        layoutWrapper: $('<div />', {
            'class': 'span1',
            'data-motopress-wrapper-type': 'layout'
        }),
        layoutWrapperSpanClass: null,

        //insert helpers
        //line
        lineHelperLeftOut: $('<div />', {
            'class': 'motopress-line-helper-left-out'
        }),
        lineHelperRightOut: $('<div />', {
            'class': 'motopress-line-helper-right-out'
        }),
        lineHelperLeftIn: $('<div />', {
            'class': 'motopress-line-helper-left-in'
        }),
        lineHelperRightIn: $('<div />', {
            'class': 'motopress-line-helper-right-in'
        }),
        lineHelperTopIn: $('<div />', {
            'class': 'motopress-line-helper-top-in'
        }),
        lineHelperBottomIn: $('<div />', {
            'class': 'motopress-line-helper-bottom-in'
        }),
        lineHelperHandleMiddle: $('<div />', {
            'class': 'motopress-line-helper-handle-middle'
        }),

        //text
        textHelperLeftOut: $('<div />', {
            'class': 'motopress-text-helper-left-out',
            text: localStorage.getItem('helperLeftOut')
        }),
        textHelperRightOut: $('<div />', {
            'class': 'motopress-text-helper-right-out',
            text: localStorage.getItem('helperRightOut')
        }),
        textHelperLeftIn: $('<div />', {
            'class': 'motopress-text-helper-left-in',
            text: localStorage.getItem('helperLeftIn')
        }),
        textHelperRightIn: $('<div />', {
            'class': 'motopress-text-helper-right-in',
            text: localStorage.getItem('helperRightIn')
        }),
        textHelperTopIn: $('<div />', {
            'class': 'motopress-text-helper-top-in',
            text: localStorage.getItem('helperTopIn')
        }),
        textHelperBottomIn: $('<div />', {
            'class': 'motopress-text-helper-bottom-in',
            text: localStorage.getItem('helperBottomIn')
        }),
        textHelperHandleMiddle: $('<div />', {
            'class': 'motopress-text-helper-handle-middle',
            text: localStorage.getItem('helperMiddle')
        }),
        //vars
        textHelperHalfSize: null,
        lineHelperThickness: null,
        lineHelperHalfThickness: null,
        handleMiddleHalfThickness: null,
        //

        helperContainer: $('<div />', {
            'class': 'motopress-helper-container'
        }).appendTo('body'),

        newBlock: $('<div />', {
            'class': 'span1'
        }),

        resizer: new MP.Resizer(),

        tools: new MP.Tools(),

        //currentHistoryItem: null,

        sidebarList: null,

        staticBlockList : null,

        loopBlockList : null,

        headerList: null,

        footerList: null,

        setup: function() {
            this.overlay.append(
                this.handleTopIn, this.handleBottomIn,
                this.handleLeftOut.clone(), this.handleRightOut.clone(),
                this.handleLeftIn, this.handleRightIn
            );

            this.helper.append(this.overlay, this.dragHandle, this.tools.tools, this.resizer.splitter.clone());

            this.emptySpanOverlay.append(this.handleLeftOut.clone(), this.handleRightOut.clone());

            this.emptySpanHelper.append(this.emptySpanOverlay, this.resizer.splitter.clone());

            this.handleWrapperLeft.append(this.wrapperHelperContainer.clone());
            this.handleWrapperRight.append(this.wrapperHelperContainer.clone());

            this.wrapperHelper.append(this.handleWrapperLeft, this.handleWrapperRight);

            this.wrapperHelperResizer.append(this.resizer.splitter.clone());

            this.layoutWrapperOverlay.append(this.handleLayoutWrapperLeftOut, this.handleLayoutWrapperRightOut);
            this.layoutWrapperHelper.append(this.layoutWrapperOverlay, this.tools.layoutWrapperTools/*, this.resizer.splitter.clone()*/);
            this.layoutWrapperSpanClass = MP.DragDrop.getSpanClass(this.layoutWrapper.prop('class').split(' '));

            var calcT = setTimeout(function() {
                MP.DragDrop.myThis.textHelperHalfSize = Math.round(MP.DragDrop.myThis.textHelperHandleMiddle.height() / 2);
                MP.DragDrop.myThis.lineHelperThickness = MP.DragDrop.myThis.lineHelperHandleMiddle.height();
                MP.DragDrop.myThis.lineHelperHalfThickness = Math.round(MP.DragDrop.myThis.lineHelperThickness / 2);
                MP.DragDrop.myThis.handleMiddleHalfThickness = Math.round(6 / 2);
                clearTimeout(calcT);
            }, 0);

            this.helperContainer.append(
                this.lineHelperLeftOut, this.lineHelperRightOut, this.lineHelperLeftIn, this.lineHelperRightIn,
                this.lineHelperTopIn, this.lineHelperBottomIn, this.lineHelperHandleMiddle,
                this.textHelperLeftOut, this.textHelperRightOut, this.textHelperLeftIn, this.textHelperRightIn,
                this.textHelperTopIn, this.textHelperBottomIn, this.textHelperHandleMiddle,
                this.wrapperHighlight
            );

            //this.newBlock.append(this.blockContent);

            this.getList();
        },

        init: function() {
            MP.DragDrop.myThis = this;
        },

        main: function() {
            this.recursiveAddHandleMiddle($('.container:not(#motopress-grid)'));
//            this.makeDroppableHandleMiddleOut();

            //this.makeEditableLayoutWrapper($('[class*="span"][data-motopress-wrapper-type]'));

            this.makeEditable($('.container:not(#motopress-grid) [class*="span"]:not([data-motopress-wrapper-type], .motopress-empty)'));
            this.makeEditableEmptySpan($('.container:not(#motopress-grid) [class*="span"].motopress-empty'));

            this.resizer.updateSplitterHeight(null, 'init');

            this.makeDraggableNewBlock();
        },

        addResponsiveUtility: function(obj) {
            obj.each(function() {
                if (!$(this).closest('.motopress-block-content').length) {
                    var json = {};
                    for (var i=0; i<MP.DragDrop.ruc.length; i++) {
                        if ($(this).hasClass(MP.DragDrop.ruc[i])) {
                            json[MP.DragDrop.ruc[i]] = true;
                            $(this).removeClass(MP.DragDrop.ruc[i]);
                        } else {
                            json[MP.DragDrop.ruc[i]] = false;
                        }
                    }
                    $(this).attr('data-motopress-responsive-utility', JSON.stringify(json));
                }
            });

            var updatePreviewTimer = setTimeout(function() {
                parent.MP.Navbar.myThis.updatePreview();
                clearTimeout(updatePreviewTimer);
            }, 0);
        },

        makeEditableLayoutWrapper: function(layoutWrapper) {
            this.addResponsiveUtility(layoutWrapper);
            layoutWrapper.not('[data-motopress-wrapper-type="content"]').each(function() {
                var $this = $(this);
                MP.DragDrop.myThis.addLayoutWrapperHelpers($this);
                //MP.DragDrop.myThis.resizer.makeSplittable($this);
                //MP.DragDrop.myThis.makeDraggableLayoutWrapper($this);
                //MP.DragDrop.myThis.makeDroppableLayoutWrapper($this);
                MP.DragDrop.myThis.tools.makeLayoutWrapperTools($this);
            });
            /*
            var timeout = setTimeout(function() {
                MP.DragDrop.myThis.tools.disableUsedContentOption();
                clearTimeout(timeout);
            }, 0);
            */
        },

        makeEditable: function(obj) {
            this.addResponsiveUtility(obj);
            obj.each(function() {
                var $this = $(this);
                if (!$this.closest('.motopress-block-content').length) {
                    MP.DragDrop.myThis.addHelpers($this);
                    MP.DragDrop.myThis.resizer.makeResizable($this);
                    MP.DragDrop.myThis.resizer.makeSplittable($this);
                    MP.DragDrop.myThis.makeDraggable($this);
                    MP.DragDrop.myThis.makeDroppable();
                    MP.DragDrop.myThis.tools.makeRemovable($this);
                    MP.DragDrop.myThis.tools.makeHoverBlock($this);
                    //MP.DragDrop.myThis.tools.makeHoverBlockTools($this);
                    var t = setTimeout(function() {
                        switch ($this.attr('data-motopress-type')) {
                            case 'dynamic-sidebar':
                                var sidebarId = $this.attr('data-motopress-sidebar-id');
                                MP.Tools.myThis.editSidebarContent($this);
                                MP.Tools.myThis.makeTypeBlockTools($this, sidebarId);
                                MP.Tools.myThis.makePopoverSidebarBlockTools($this);
                                break;
                            case 'static-sidebar':
                                var sidebarFile = $this.attr('data-motopress-sidebar-file');
                                MP.Tools.myThis.editSidebarContent($this);
                                MP.Tools.myThis.makeTypeBlockTools($this, sidebarFile);
                                MP.Tools.myThis.makePopoverSidebarBlockTools($this);
                                break;
                            case 'static':
                                MP.Tools.myThis.editStaticContent($this);
//                                MP.Tools.myThis.makePopoverStaticBlockTools($this);
                                MP.Tools.myThis.makeTypeStaticBlockTools($this);
                                break;
                            case 'loop':
                                MP.Tools.myThis.editLoopContent($this);
                                MP.Tools.myThis.makeTypeLoopBlockTools($this);
                                MP.Tools.myThis.makePopoverLoopBlockTools($this);
                                break;
                        }
                        MP.DragDrop.myThis.tools.makeTooltipBlockTools($this);
                        clearTimeout(t);
                    }, 0);
                }
            });
        },

        makeEditableEmptySpan: function(obj) {
            if (obj.length) {
                obj.each(function() {
                    if (!$(this).children('.motopress-helper').length) {
                        $(this).append(MP.DragDrop.myThis.emptySpanHelper.clone());
                    }
                });

                var t = setTimeout(function() {
                    MP.DragDrop.myThis.makeDroppable();
                    clearTimeout(t);
                }, 0);

                this.resizer.makeSplittable(obj);
            }
        },

        addLayoutWrapperHelpers: function(layoutWrapper) {
            layoutWrapper.each(function(){
                $(this).prepend(MP.DragDrop.myThis.layoutWrapperHelper.clone());
            });
        },

        addHelpers: function(obj) {
            obj.each(function() {
                if (!$(this).children('.motopress-block-content').length && !$(this).children('.row').length) {
                    $(this).find('script').remove().end().wrapInner(MP.DragDrop.myThis.blockContent.clone());
                }

                if (!$(this).children('.motopress-helper').length) {
                    /*if ($(this).hasClass('motopress-empty').length) {
                        $(this).prepend(MP.DragDrop.myThis.emptySpanHelper.clone());
                    } else */if ($(this).children('.row').length) {
                        $(this).attr('data-motopress-wrapper-id', MP.DragDrop.myThis.wrapperId);
                        $(this).prepend(MP.DragDrop.myThis.wrapperHelperResizer.clone());
                        var mainWrapperHelper = null;
                        if($(this).parent('.row').parent('[class*="span"][data-motopress-wrapper-type]').length) {
                            mainWrapperHelper = MP.DragDrop.myThis.wrapperHelper.clone();
                            MP.DragDrop.myThis.droppableWrapperHelper(mainWrapperHelper);
                            $(this).prepend(mainWrapperHelper);
                        } else {
                            mainWrapperHelper = $(this).closest('[class*="span"]:has(".motopress-wrapper-helper")').children('.motopress-wrapper-helper');
                        }
                        MP.DragDrop.myThis.addHandleWrapper(mainWrapperHelper);
                        MP.DragDrop.myThis.droppableHandleWrapper(mainWrapperHelper);
                        MP.DragDrop.myThis.wrapperId++;
                    } else {
                        $(this).append(MP.DragDrop.myThis.helper.clone());
                    }
                }
                /*if (!$(this).children('.motopress-helper').length) {
                    $(this).append(helper.clone());
                }*/
            });
        },

        makeDraggableLayoutWrapper: function(layoutWrapper) {
            layoutWrapper.draggable({
                cursor: 'move',
                helper: 'clone',
                handle: '.motopress-drag-layout-wrapper-handle',
                opacity: '0',
                zIndex: 1,
                start: function() {
                    $(this).css('opacity', 0.3);
                },
                stop: function() {
                    $(this).css('opacity', 1);
                }
            });
        },

        makeDraggable: function(obj) {
            obj.draggable({
                cursor: 'move',
                helper: 'clone',
                handle: '.motopress-drag-handle',
                opacity: '0',
                zIndex: 1,
                start: function() {
                    $('.motopress-handle-middle-in').addClass('motopress-start-drag');
                    $(this).css('opacity', 0.3);

                    $('[class*="span"][data-motopress-type]').addClass('motopress-block-highlight');
                    $('.motopress-layout-wrapper-tools, .motopress-tools').hide();
                },
                stop: function() {
                    $('.motopress-handle-middle-in').removeClass('motopress-start-drag');
                    $(this).css('opacity', '');

                    $('[class*="span"][data-motopress-type]').removeClass('motopress-block-highlight');
                    $('.motopress-layout-wrapper-tools, .motopress-tools').show();
                    MP.Resizer.myThis.updateSplitterHeight(obj, 'drag');
                }
            }).removeClass('ui-draggable');
        },

        makeDraggableNewBlock: function() {
            $('.motopress-new-block').draggable({
                cursor: 'move',
                helper: 'clone',
                zIndex: 10002,
                appendTo: '.container:not(#motopress-grid):first',
                start: function(e, ui) {
                    $('.motopress-handle-middle-in').addClass('motopress-start-drag');

                    $('[class*="span"][data-motopress-type]').addClass('motopress-block-highlight');
                    $('.motopress-layout-wrapper-tools, .motopress-tools').hide();
                },
                stop: function() {
                    $('.motopress-handle-middle-in').removeClass('motopress-start-drag');

                    $('[class*="span"][data-motopress-type]').removeClass('motopress-block-highlight');
                    $('.motopress-layout-wrapper-tools, .motopress-tools').show();
                }
            });
        },

        makeDroppableLayoutWrapper: function(layoutWrapper) {
            layoutWrapper.find('.motopress-handle-layout-wrapper-left-out, .motopress-handle-layout-wrapper-right-out').droppable({
                accept: '[class*="span"][data-motopress-wrapper-type], .motopress-new-block[data-type="wrapper"], .motopress-new-block[data-type="content"]',
                tolerance: 'pointer',
                drop: function(e, ui) {
                    MP.DragDrop.myThis.hideLineTextHelper($(this).attr('data-motopress-position'));

                    var span = $(this).closest('[class*="span"][data-motopress-wrapper-type]');
                    var spanClassDraggable = null;
                    var rowFrom = null;
                    var rowTo = span.parent('.row');

                    var myUI = null;
                    var block = null;
                    var isNewBlock = ui.draggable.hasClass('motopress-new-block');
                    if (!isNewBlock) {
                        myUI = ui;
                        block = myUI.draggable;
                        rowFrom = block.parent('.row');
                    } else {
                        myUI = null;
                        block = MP.DragDrop.myThis.newBlock.clone();
                        MP.DragDrop.myThis.makeEditable(block);
                        rowFrom = null;
                        block.attr({
                            'data-motopress-loop-file': 'loop/' + ui.draggable.attr('data-motopress-loop-file'),
                            'data-motopress-type': 'loop'
                        });
                        var rowWrapper = block.wrap($('<div />', {
                            'class': 'row'
                        })).parent('.row');
                        var layoutWrapper = rowWrapper.wrap(MP.DragDrop.myThis.layoutWrapper.clone()).parent('[class*="span"]');
                        layoutWrapper.attr({
                            'data-motopress-type': ui.draggable.attr('data-motopress-type'),
                            'data-motopress-new': ui.draggable.attr('data-motopress-new')
                        });

                        if (ui.draggable.attr('data-type') == 'content') {
                            var loopFile = ui.draggable.attr('data-motopress-loop-file');
                            if (typeof(loopFile) != 'undefined') {
                                block.attr('data-motopress-loop-file', 'loop/' + loopFile);
                            }
                            block.attr('data-motopress-type', 'loop');
                        }

                        block = layoutWrapper;
                    }
                    spanClassDraggable = MP.DragDrop.getSpanClass(block.prop('class').split(' '));
                    spanClassDraggable = MP.DragDrop.myThis.removeEmptyBlocks(block, spanClassDraggable);

                    switch($(this).attr('data-motopress-position')) {
                        case 'left-out':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI)) {
                                span.before(block);

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    if (rowFrom == null || rowFrom[0] != rowTo[0]) {
                                        if(isNewBlock) {
                                            if(ui.draggable.attr('data-type') == 'content') {
//                                                var newBlock = MP.LeftMenu.myThis.leftMenu.find('.motopress-new-block[data-type="content"]');
//                                                newBlock.draggable('disable');
                                                var blockContent = block.children('.row').children('[class*="span"]').children('.motopress-block-content');
                                                var loopFile = ui.draggable.attr('data-motopress-loop-file');
                                                var entityId = ui.draggable.attr('data-motopress-entity-id');
                                                MP.DragDrop.myThis.getContent(blockContent, loopFile, entityId);
                                            }
                                            MP.DragDrop.myThis.addHandleMiddle(rowWrapper, MP.DragDrop.myThis.layoutWrapperSpanClass);
                                            MP.DragDrop.myThis.makeEditableLayoutWrapper(block);
                                        }
                                        MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    }
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;

                        case 'right-out':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI)) {
                                span.after(block);

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    if (rowFrom == null || rowFrom[0] != rowTo[0]) {
                                        if(isNewBlock) {
                                            if(ui.draggable.attr('data-type') == 'content') {
//                                                var newBlock = MP.LeftMenu.myThis.leftMenu.find('.motopress-new-block[data-type="content"]');
//                                                newBlock.draggable('disable');
                                                var blockContent = block.children('.row').children('[class*="span"]').children('.motopress-block-content');
                                                var loopFile = ui.draggable.attr('data-motopress-loop-file');
                                                var entityId = ui.draggable.attr('data-motopress-entity-id');
                                                MP.DragDrop.myThis.getContent(blockContent, loopFile, entityId);
                                            }
                                            MP.DragDrop.myThis.addHandleMiddle(rowWrapper, MP.DragDrop.myThis.layoutWrapperSpanClass);
                                            MP.DragDrop.myThis.makeEditableLayoutWrapper(block);
                                        }
                                        MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    }
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;
                    }


                    var droppableTimeout = setTimeout(function() {
                        if(isNewBlock) {
                            MP.DragDrop.myThis.makeDroppable();
                            MP.Resizer.myThis.updateSplitterHeight(block, 'drop');
                        }
                        MP.DragDrop.myThis.resizer.updateResizableOptions(block, rowFrom, rowTo);
                        clearTimeout(droppableTimeout);
                    }, 0);

                },
                over: function() {
                    MP.DragDrop.myThis.showLineTextHelper($(this).closest('[class*="span"][data-motopress-wrapper-type]'), $(this).attr('data-motopress-position'));
                },
                out: function() {
                    MP.DragDrop.myThis.hideLineTextHelper($(this).attr('data-motopress-position'));
                }
            });
        },

        makeDroppableHandleMiddleOut: function() {
            $('.motopress-handle-middle-out').droppable({
                accept: '[class*="span"][data-motopress-wrapper-type], .motopress-new-block[data-type="wrapper"]',
                tolerance: 'pointer',
                drop: function(e, ui) {
                    MP.DragDrop.myThis.hideLineTextHelper($(this).attr('data-motopress-position'));

                    var spanClass = 'span12';
                    var spanClassDraggable = null;
                    var rowFrom = null;
                    var rowTo = $(this).closest('.row');
                    var myUI = null;
                    var block = null;
                    var id = null;
                    var file = null;
                    var isNewBlock = ui.draggable.hasClass('motopress-new-block');
                    if (!isNewBlock) {
                        myUI = ui;
                        block = myUI.draggable;
                        rowFrom = block.parent('.row');
                        id = rowFrom.attr('data-motopress-id');
                        file = rowFrom.attr('data-motopress-file');
                    } else {
                        myUI = null;
                        block = MP.DragDrop.myThis.newBlock.clone();
                        MP.DragDrop.myThis.makeEditable(block);
                        rowFrom = null;
                    }
                    spanClassDraggable = MP.DragDrop.getSpanClass(block.prop('class').split(' '));
                    spanClassDraggable = MP.DragDrop.myThis.removeEmptyBlocks(block, spanClassDraggable);

                    rowTo = block.wrap($('<div />', {
                        'class': 'row',
                        'data-motopress-id': id,
                        'data-motopress-file': file
                    })).parent('.row');

                    if(MP.DragDrop.myThis.isLayoutWrapper(block)) {
                        $(this).after(rowTo);
                    } else {
                        var layoutWrapper = rowTo.wrap(MP.DragDrop.myThis.layoutWrapper.clone().removeClass(MP.DragDrop.myThis.layoutWrapperSpanClass).addClass(spanClass)).parent('[class*="span"]');
                        layoutWrapper.attr({
                            'data-motopress-type': ui.draggable.attr('data-motopress-type'),
                            'data-motopress-new': ui.draggable.attr('data-motopress-new')
                        });
                        var parentRowTo = layoutWrapper.wrap('<div class="row" />').parent('.row');
                        $(this).after(parentRowTo);
                        if(isNewBlock) {
                            if(ui.draggable.attr('data-type') == 'content') {
//                                var newBlock = MP.LeftMenu.myThis.leftMenu.find('.motopress-new-block[data-type="content"]');
//                                newBlock.draggable('disable');
                                var blockContent = block.children('.motopress-block-content');

                                var loopFile = ui.draggable.attr('data-motopress-loop-file');
                                if (typeof loopFile != 'undefined') {
                                    block.attr('data-motopress-loop-file', 'loop/' + loopFile);
                                }
                                block.attr('data-motopress-type', 'loop');
                                var entityId = ui.draggable.attr('data-motopress-entity-id');
                                MP.DragDrop.myThis.getContent(blockContent, loopFile, entityId);
                            }
                        }
                    }

                    var t = setTimeout(function() {
                        rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                        if(MP.DragDrop.myThis.isLayoutWrapper(block)) {
                            MP.DragDrop.myThis.addHandleMiddle(rowTo, 'container');
                        } else {
                            MP.DragDrop.myThis.addHandleMiddle(rowTo, spanClass);
                            MP.DragDrop.myThis.addHandleMiddle(parentRowTo, 'container');
                            MP.DragDrop.myThis.makeEditableLayoutWrapper(layoutWrapper);
                            if(isNewBlock) {
                                MP.DragDrop.myThis.makeDroppable();
                            }
                        }
                        MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                        clearTimeout(t);
                    }, 0);

                    var droppableTimeout = setTimeout(function() {
                        MP.DragDrop.myThis.makeDroppableHandleMiddleOut();
                        MP.DragDrop.myThis.resizer.updateResizableOptions(block, rowFrom, rowTo);
                        if (isNewBlock) MP.Resizer.myThis.updateSplitterHeight(block, 'drop');
                        clearTimeout(droppableTimeout);
                    }, 0);
                },
                over: function(e, ui) {
                    MP.DragDrop.myThis.showLineTextHelper($(this), $(this).attr('data-motopress-position'));
                },
                out: function(e, ui) {
                    MP.DragDrop.myThis.hideLineTextHelper($(this).attr('data-motopress-position'));
                }
            });
        },

        makeDroppable: function() {
            $('.motopress-handle-top-in, .motopress-handle-bottom-in, .motopress-handle-left-out, .motopress-handle-right-out, .motopress-handle-left-in, .motopress-handle-right-in, .motopress-handle-middle-in').droppable({
                accept: '[class*="span"]:not([data-motopress-wrapper-type]), .motopress-new-block[data-type="static"], .motopress-new-block[data-type="loop"], .motopress-new-block[data-type="dynamic-sidebar"]',
                tolerance: 'pointer',

                drop: function(e, ui) {
                    MP.DragDrop.myThis.hideLineTextHelper($(this).attr('data-motopress-position'));

                    var span = $(this).closest('[class*="span"]');
                    if (span.length == 0) span = $(this);
                    var spanClass = MP.DragDrop.getSpanClass(span.prop('class').split(' '));
                    var spanClassDraggable = null;
                    var rowFrom = null;
                    var rowTo = $(this).closest('.row');

                    var myUI = null;
                    var block = null;
                    var isNewBlock = ui.draggable.hasClass('motopress-new-block');
                    if (!isNewBlock) {
                        myUI = ui;
                        block = myUI.draggable;
                        rowFrom = block.parent('.row');
                    } else {
                        myUI = null;
                        block = MP.DragDrop.myThis.newBlock.clone();

                        block.attr('data-motopress-type', ui.draggable.attr('data-type'));

                        MP.DragDrop.myThis.makeEditable(block);
                        rowFrom = null;

                        if (block.attr('data-motopress-type') == 'loop') {
                            block.attr('data-motopress-loop-file', ui.draggable.attr('data-motopress-loop-file'));
                        }
                    }
                    spanClassDraggable = MP.DragDrop.getSpanClass(block.prop('class').split(' '));
                    spanClassDraggable = MP.DragDrop.myThis.removeEmptyBlocks(block, spanClassDraggable);

                    //history
                    /*
                    var id = block.attr('data-motopress-id');
                    var handleSpanId = span.attr('data-motopress-id');
                    var fileFrom = block.attr('data-motopress-file');
                    var fileTo = span.attr('data-motopress-file');
                    */
                    var handle = $(this).attr('data-motopress-position');
                    /*
                    var historyItem = new MP.History.Item(id, handleSpanId, []);
                    MP.DragDrop.myThis.currentHistoryItem = historyItem;
                    */

                    switch (handle) {
                        case 'top-in':
                            var draggableSpan = block.wrap('<div class="row" />').closest('.row');
                            var row = span.wrap('<div class="row" />').closest('.row').wrap('<div class="'+spanClass+'" />');
                            row.before(draggableSpan);

                            var wrapperSpan = row.parent('[class*="span"]');
                            wrapperSpan.attr({
                                'data-motopress-id': parent.MP.Utils.uniqid(),
                                //'data-motopress-file': fileTo,
                                'data-motopress-wrapper-id': MP.DragDrop.myThis.wrapperId
                            }).prepend(MP.DragDrop.myThis.wrapperHelperResizer.clone());
                            MP.DragDrop.myThis.resizer.makeSplittable(wrapperSpan);

                            var mainWrapperHelper = null;

                            if(wrapperSpan.parent('.row').parent('[class*="span"][data-motopress-wrapper-type]').length) {
                                mainWrapperHelper = MP.DragDrop.myThis.wrapperHelper.clone();
                                MP.DragDrop.myThis.droppableWrapperHelper(mainWrapperHelper);
                                wrapperSpan.prepend(mainWrapperHelper);
                            } else {
                                mainWrapperHelper = wrapperSpan.closest('[class*="span"]:has(".motopress-wrapper-helper")').children('.motopress-wrapper-helper');
                            }

                            MP.DragDrop.myThis.addHandleWrapper(mainWrapperHelper);
                            MP.DragDrop.myThis.droppableHandleWrapper(mainWrapperHelper);
                            MP.DragDrop.myThis.wrapperId++;

                            var t = setTimeout(function() {
                                rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                MP.DragDrop.myThis.addHandleMiddle(row, spanClass);
                                MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                clearTimeout(t);
                            }, 0);
                            break;

                        case 'bottom-in':
                            var draggableSpan = block.wrap('<div class="row" />').closest('.row');
                            var row = span.wrap('<div class="row" />').closest('.row').wrap('<div class="'+spanClass+'" />');
                            row.after(draggableSpan);

                            var wrapperSpan = row.parent('[class*="span"]');
                            wrapperSpan.attr({
                                'data-motopress-id': parent.MP.Utils.uniqid(),
                                'data-motopress-wrapper-id': MP.DragDrop.myThis.wrapperId
                            }).prepend(MP.DragDrop.myThis.wrapperHelperResizer.clone());
                            MP.DragDrop.myThis.resizer.makeSplittable(wrapperSpan);

                            var mainWrapperHelper = null;

                            if (wrapperSpan.parent('.row').parent('[class*="span"][data-motopress-wrapper-type]').length) {
                                mainWrapperHelper = MP.DragDrop.myThis.wrapperHelper.clone();
                                MP.DragDrop.myThis.droppableWrapperHelper(mainWrapperHelper);
                                wrapperSpan.prepend(mainWrapperHelper);
                            } else {
                                mainWrapperHelper = wrapperSpan.closest('[class*="span"]:has(".motopress-wrapper-helper")').children('.motopress-wrapper-helper');
                            }

                            MP.DragDrop.myThis.addHandleWrapper(mainWrapperHelper);
                            MP.DragDrop.myThis.droppableHandleWrapper(mainWrapperHelper)
                            MP.DragDrop.myThis.wrapperId++;

                            var t = setTimeout(function() {
                                rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                MP.DragDrop.myThis.addHandleMiddle(row, spanClass);
                                MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                clearTimeout(t);
                            }, 0);
                            break;

                        case 'left-out':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI)) {
                                if (span.parent().length) span.before(block);

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    if (rowFrom == null || rowFrom[0] != rowTo[0]) {
                                        MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    }
                                    //console.log(MP.History.items);
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;

                        case 'right-out':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI)) {
                                if (span.parent().length) span.after(block);

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    if (rowFrom == null || rowFrom[0] != rowTo[0]) {
                                        MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    }
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;

                        case 'left-in':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI) && MP.DragDrop.getSpanNumber(spanClass) > 1) {
                                var row = span.wrap('<div class="row" />').closest('.row').wrap('<div class="'+spanClass+'" />');
                                span.before(block);

                                var wrapperSpan = row.parent('[class*="span"]');
                                wrapperSpan.attr({
                                    'data-motopress-id': parent.MP.Utils.uniqid(),
                                    'data-motopress-wrapper-id': MP.DragDrop.myThis.wrapperId
                                }).prepend(MP.DragDrop.myThis.wrapperHelperResizer.clone());
                                MP.DragDrop.myThis.resizer.makeSplittable(wrapperSpan);

                                var mainWrapperHelper = null;

                                if (wrapperSpan.parent('.row').parent('[class*="span"][data-motopress-wrapper-type]').length) {
                                    mainWrapperHelper = MP.DragDrop.myThis.wrapperHelper.clone();
                                    MP.DragDrop.myThis.droppableWrapperHelper(mainWrapperHelper);
                                    wrapperSpan.prepend(mainWrapperHelper);
                                } else {
                                    mainWrapperHelper = wrapperSpan.closest('[class*="span"]:has(".motopress-wrapper-helper")').children('.motopress-wrapper-helper');
                                }

                                MP.DragDrop.myThis.addHandleWrapper(mainWrapperHelper);
                                MP.DragDrop.myThis.droppableHandleWrapper(mainWrapperHelper)
                                MP.DragDrop.myThis.wrapperId++;

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    MP.DragDrop.myThis.addHandleMiddle(row, spanClass);
                                    MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;

                        case 'right-in':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI) && MP.DragDrop.getSpanNumber(spanClass) > 1) {
                                var row = span.wrap('<div class="row" />').closest('.row').wrap('<div class="'+spanClass+'" />');
                                span.after(block);

                                var wrapperSpan = row.parent('[class*="span"]');
                                wrapperSpan.attr({
                                    'data-motopress-id': parent.MP.Utils.uniqid(),
                                    'data-motopress-wrapper-id': MP.DragDrop.myThis.wrapperId
                                }).prepend(MP.DragDrop.myThis.wrapperHelperResizer.clone());
                                MP.DragDrop.myThis.resizer.makeSplittable(wrapperSpan);

                                var mainWrapperHelper = null;

                                if (wrapperSpan.parent('.row').parent('[class*="span"][data-motopress-wrapper-type]').length) {
                                    mainWrapperHelper = MP.DragDrop.myThis.wrapperHelper.clone();
                                    MP.DragDrop.myThis.droppableWrapperHelper(mainWrapperHelper);
                                    wrapperSpan.prepend(mainWrapperHelper);
                                } else {
                                    mainWrapperHelper = wrapperSpan.closest('[class*="span"]:has(".motopress-wrapper-helper")').children('.motopress-wrapper-helper');
                                }

                                MP.DragDrop.myThis.addHandleWrapper(mainWrapperHelper);
                                MP.DragDrop.myThis.droppableHandleWrapper(mainWrapperHelper)
                                MP.DragDrop.myThis.wrapperId++;

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    MP.DragDrop.myThis.addHandleMiddle(row, spanClass);
                                    MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;

                        case 'middle-in':
                            var flag;
                            if(spanClass == null) {
                                spanClass = 'span12';
                                flag = true;
                            }

                            rowTo = block.wrap('<div class="row" />').closest('.row');
                            $(this).after(rowTo);

                            var t = setTimeout(function() {
                                rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                if (flag) spanClass = 'container';
                                MP.DragDrop.myThis.addHandleMiddle(rowTo, spanClass);
                                MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                clearTimeout(t);
                            }, 0);
                            break;
                    }

                    var droppableTimeout = setTimeout(function() {
                        /*if (block.attr('data-motopress-type') == 'loop') {
                            var blockContent = block.children('.motopress-block-content');
                            MP.DragDrop.myThis.getLoop(blockContent);
                        }*/

                        MP.DragDrop.myThis.makeDroppable();
                        MP.DragDrop.myThis.resizer.updateResizableOptions(block, rowFrom, rowTo);
                        if (isNewBlock) MP.Resizer.myThis.updateSplitterHeight(block, 'drop');
                        clearTimeout(droppableTimeout);
                    }, 0);
                },
                over: function() {
                    var span = ($(this).attr('data-motopress-position') != 'middle-in') ? $(this).closest('[class*="span"]') : $(this);
                    MP.DragDrop.myThis.showLineTextHelper(span, $(this).attr('data-motopress-position'));
                },
                out: function() {
                    MP.DragDrop.myThis.hideLineTextHelper($(this).attr('data-motopress-position'));
                }
            });
        },

        showLineTextHelper: function(span, handle) {
            var spanOffset = span.offset();

            switch(handle) {
                case 'left-out':
                    this.lineHelperLeftOut.css({
                        height: span.outerHeight(),
                        top: spanOffset.top,
                        left: spanOffset.left
                    }).show();
                    this.textHelperLeftOut.css({
                        top: this.lineHelperLeftOut.offset().top,
                        left: this.lineHelperLeftOut.offset().left - this.textHelperLeftOut.outerWidth() + this.lineHelperThickness
                    }).show();
                    break;

                case 'right-out':
                    this.lineHelperRightOut.css({
                        height: span.outerHeight(),
                        top: spanOffset.top,
                        left: spanOffset.left + span.width() - this.lineHelperThickness
                    }).show();
                    this.textHelperRightOut.css({
                        top: this.lineHelperRightOut.offset().top,
                        left: this.lineHelperRightOut.offset().left
                    }).show();
                    break;

                case 'left-in':
                    this.lineHelperLeftIn.css({
                        height: span.outerHeight(),
                        top: spanOffset.top,
                        left: spanOffset.left
                    }).show();
                    this.textHelperLeftIn.css({
                        top: this.lineHelperLeftIn.offset().top,
                        left: this.lineHelperLeftIn.offset().left
                    }).show();
                    break;

                case 'right-in':
                    this.lineHelperRightIn.css({
                        height: span.outerHeight(),
                        top: spanOffset.top,
                        left: spanOffset.left + span.width() - this.lineHelperThickness
                    }).show();
                    this.textHelperRightIn.css({
                        top: this.lineHelperRightIn.offset().top,
                        left: this.lineHelperRightIn.offset().left - this.textHelperRightIn.outerWidth() + this.lineHelperThickness
                    }).show();
                    break;

                case 'top-in':
                    this.lineHelperTopIn.css({
                        width: span.width(),
                        top: spanOffset.top,
                        left: spanOffset.left
                    }).show();
                    this.textHelperTopIn.css({
                        top: spanOffset.top - this.textHelperHalfSize,
                        left: spanOffset.left
                    }).show();
                    break;

                case 'bottom-in':
                    this.lineHelperBottomIn.css({
                        width: span.width(),
                        top: spanOffset.top + span.outerHeight() - this.lineHelperThickness,
                        left: spanOffset.left
                    }).show();
                    this.textHelperBottomIn.css({
                        top: this.lineHelperBottomIn.offset().top - this.textHelperHalfSize,
                        left: this.lineHelperBottomIn.offset().left
                    }).show();
                    break;

                case 'middle-out':
                    this.lineHelperHandleMiddle.css({
                        width: span.width(),
                        top: span.offset().top + this.handleMiddleHalfThickness - this.lineHelperHalfThickness,
                        left: span.offset().left
                    }).show();
                    this.textHelperHandleMiddle.css({
                        top: this.lineHelperHandleMiddle.offset().top - this.textHelperHalfSize,
                        left: this.lineHelperHandleMiddle.offset().left
                    }).show();
                    break;

                case 'middle-in':
                    this.lineHelperHandleMiddle.css({
                        width: span.width(),
                        top: span.offset().top + this.handleMiddleHalfThickness - this.lineHelperHalfThickness,
                        left: span.offset().left
                    }).show();
                    this.textHelperHandleMiddle.css({
                        top: this.lineHelperHandleMiddle.offset().top - this.textHelperHalfSize,
                        left: this.lineHelperHandleMiddle.offset().left
                    }).show();

                    var wrapper = span.closest('[class*="span"]');
                    if (wrapper.length) {
                        var wrapperOffset = wrapper.offset();
                        this.wrapperHighlight.css({
                            'width': wrapper.width(),
                            'height': wrapper.height(),
                            'top': wrapperOffset.top,
                            'left': wrapperOffset.left
                        }).show();
                    }
                    break;
            }
        },

        hideLineTextHelper: function(handle) {
            switch (handle) {
                case 'left-out':
                    this.lineHelperLeftOut.hide();
                    this.textHelperLeftOut.hide();
                    break;

                case 'right-out':
                    this.lineHelperRightOut.hide();
                    this.textHelperRightOut.hide();
                    break;

                case 'left-in':
                    this.lineHelperLeftIn.hide();
                    this.textHelperLeftIn.hide();
                    break;

                case 'right-in':
                    this.lineHelperRightIn.hide();
                    this.textHelperRightIn.hide();
                    break;

                case 'top-in':
                    this.lineHelperTopIn.hide();
                    this.textHelperTopIn.hide();
                    break;

                case 'bottom-in':
                    this.lineHelperBottomIn.hide();
                    this.textHelperBottomIn.hide();
                    break;

                case 'middle-out':
                    this.lineHelperHandleMiddle.hide();
                    this.textHelperHandleMiddle.hide();
                    this.wrapperHighlight.hide();
                    break;

                case 'middle-in':
                    this.lineHelperHandleMiddle.hide();
                    this.textHelperHandleMiddle.hide();
                    this.wrapperHighlight.hide();
                    break;
            }
        },

        removeEmptyBlocks: function(block, spanClassDraggable) {
            var prevEmpty = block.prev('.motopress-empty');
            var nextEmpty = block.next('.motopress-empty');
            var prevEmptySpanNumber = 0;
            var nextEmptySpanNumber = 0;
            if (typeof prevEmpty[0] != 'undefined') prevEmptySpanNumber = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(prevEmpty.prop('class').split(' ')));
            if (typeof nextEmpty[0] != 'undefined') nextEmptySpanNumber = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(nextEmpty.prop('class').split(' ')));
            if (typeof prevEmpty[0] != 'undefined' || typeof nextEmpty[0] != 'undefined') {
                block.removeClass(spanClassDraggable);
                spanClassDraggable = 'span' + (MP.DragDrop.getSpanNumber(spanClassDraggable) + prevEmptySpanNumber + nextEmptySpanNumber);
                block.addClass(spanClassDraggable);
                prevEmpty.remove();
                nextEmpty.remove();
            }
            return spanClassDraggable;
        },

        clearIfEmpty: function(rowFrom) {
            if (rowFrom != null) {
                if (!rowFrom.children('[class*="span"]').length) {
                    console.log('empty');
                    var i = 0;
                    while (rowFrom.parent('.container').length == 0) {
                        //if (!rowFrom.siblings('.row, [class*="span"]').length) {
                        if (!rowFrom.siblings('.row, [class*="span"]').length && !rowFrom.parent('[class*="span"][data-motopress-wrapper-type]').length) {
                            if (rowFrom.parent('[class*="span"]').length) {
                                var wrapperId = rowFrom.parent('[class*="span"]').attr('data-motopress-wrapper-id');
                                $('[data-motopress-wrapper-id="'+ wrapperId +'"].motopress-handle-wrapper').remove();
                            }
                            //rowFrom.siblings('.motopress-handle-middle').remove().end().siblings('.motopress-wrapper-helper').remove().end().unwrap();

                            //var id = rowFrom.parent().attr('data-motopress-id');
                            //console.log(rowFrom.parent());
                            //MP.DragDrop.myThis.currentHistoryItem.action.push(new MP.History.UnwrapAction(id));

                            /*
                            if(rowFrom.parent('[class*="span"]').attr('data-motopress-type') == 'content') {
                                var contentBlock = MP.LeftMenu.myThis.leftMenu.find('.motopress-new-block[data-type="content"]');
                                contentBlock.draggable('enable');
                            }
                            */

                            rowFrom.siblings('.motopress-handle-middle-out, .motopress-handle-middle-in, .motopress-wrapper-helper, .motopress-helper').remove().end().unwrap();
                        } else {
                            break;
                        }
                        i++;
                        if (i == 100) {
                            console.log('LOOPED IN `clearIfEmpty()`');
                            break;
                        }
                    }

                    var flag = false;
                    var newRowFrom = null;
                    if (!rowFrom.siblings('.row').length) {
                        flag = true;
                        //if (rowFrom.parent('[class*="span"]').length) {
                        if (rowFrom.parent('[class*="span"]:not([data-motopress-wrapper-type])').length) {
                            newRowFrom = rowFrom.parent('[class*="span"]').parent('.row');
                        } else if (rowFrom.parent('.row').length) {
                            newRowFrom = rowFrom.parent('.row');
                        }
                    }

                    rowFrom.prev('.motopress-handle-middle-out, .motopress-handle-middle-in').remove().end().remove();

                    return flag ? newRowFrom : rowFrom;
                }
            }
            return rowFrom;
        },

        recursiveAddHandleMiddle: function(span) {
            span.children('.row').each(function(index) {
                var handleMiddle = null;
                if($(this).parent('.container').length){
                    handleMiddle = MP.DragDrop.myThis.handleMiddleOut;
                } else {
                    handleMiddle = MP.DragDrop.myThis.handleMiddleIn;
                }

                if(index == 0) $(this).before(handleMiddle.clone());
                $(this).after(handleMiddle.clone());

                if ($(this).children('[class*="span"]').children('.row').length) {
                    $(this).children('[class*="span"]').each(function() {
                        MP.DragDrop.myThis.recursiveAddHandleMiddle($(this));
                    });
                } else if (!$(this).children('[class*="span"][data-motopress-wrapper-type]').children('.row').length) {
                    $(this).children('[class*="span"][data-motopress-wrapper-type]').prepend(MP.DragDrop.myThis.handleMiddleIn.clone());
                }
            });
        },

        addHandleMiddle: function(row, spanClass) {
            row.parent('.'+spanClass).children('.row').each(function(index) {
                var handleMiddle = null;
                if($(this).parent('.container').length){
                    handleMiddle = MP.DragDrop.myThis.handleMiddleOut;
                } else {
                    handleMiddle = MP.DragDrop.myThis.handleMiddleIn;
                }

                if(index == 0) {
                    var prev = $(this).prevAll('.motopress-handle-middle-out, .motopress-handle-middle-in');
                    if(prev.length == 0){
                        $(this).before(handleMiddle.clone());
                    }else {
                        prev.each(function(i, el){
                            if(i != 0) {
                                $(el).remove();
                            }
                        });
                    }
                }
                var next = $(this).nextUntil('.row', '.motopress-handle-middle-out, .motopress-handle-middle-in');
                if(next.length == 0) {
                    $(this).after(handleMiddle.clone());
                } else {
                    next.each(function(i, el){
                        if(i != 0) {
                            $(el).remove();
                        }
                    });
                }
            });
        },

        addHandleWrapper: function(mainWrapperHelper) {
            mainWrapperHelper
                .find('.motopress-handle-wrapper-left > .motopress-wrapper-helper-container')
                .append(this.handleWrapper.clone().attr({
                    'data-motopress-wrapper-id': this.wrapperId,
                    'data-motopress-position': 'wrapper-left'
                }));
            mainWrapperHelper
                .find('.motopress-handle-wrapper-right > .motopress-wrapper-helper-container')
                .append(this.handleWrapper.clone().attr({
                    'data-motopress-wrapper-id': this.wrapperId,
                    'data-motopress-position': 'wrapper-right'
                }));
        },

        droppableWrapperHelper: function(wrapperHelper) {
            wrapperHelper.children('.motopress-handle-wrapper-left, .motopress-handle-wrapper-right').droppable({
                accept: '[class*="span"]:not([data-motopress-wrapper-type]), .motopress-new-block',
                hoverClass: 'motopress-hover-wrapper-helper',
                tolerance: 'pointer',
                over: function() {
                    $(this).css('opacity', 1);
                },
                out: function() {
                    $(this).css('opacity', 0);
                },
                drop: function() {
                    $(this).css('opacity', 0);
                }
            });
        },

        droppableHandleWrapper: function(wrapperHelper) {
            wrapperHelper.find('.motopress-handle-wrapper').droppable({
                hoverClass: 'motopress-hover-handle-wrapper',
                tolerance: 'pointer',
                drop: function(e, ui) {
                    MP.DragDrop.myThis.wrapperHighlight.hide();
                    var wrapperId = $(this).attr('data-motopress-wrapper-id');
                    var span = $('[data-motopress-wrapper-id="'+ wrapperId +'"][class*="span"]');
                    var spanClassDraggable = null;
                    var rowFrom = null;
                    var rowTo = span.parent('.row');

                    var myUI = null;
                    var block = null;
                    var isNewBlock = ui.draggable.hasClass('motopress-new-block');
                    if (!isNewBlock) {
                        myUI = ui;
                        block = myUI.draggable;
                        rowFrom = block.parent('.row');
                    } else {
                        myUI = null;
                        block = MP.DragDrop.myThis.newBlock.clone();
                        block.attr('data-motopress-type', ui.draggable.attr('data-type'));
                        MP.DragDrop.myThis.makeEditable(block);
                        rowFrom = null;
                    }
                    spanClassDraggable = MP.DragDrop.getSpanClass(block.prop('class').split(' '));

                    spanClassDraggable = MP.DragDrop.myThis.removeEmptyBlocks(block, spanClassDraggable);

                    switch($(this).attr('data-motopress-position')) {
                        case 'wrapper-left':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI)) {
                                span.before(block);

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    if (rowFrom == null || rowFrom[0] != rowTo[0]) {
                                        MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    }
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;

                        case 'wrapper-right':
                            if (MP.DragDrop.myThis.canInsert(rowTo, myUI)) {
                                span.after(block);

                                var t = setTimeout(function() {
                                    rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);
                                    if (rowFrom == null || rowFrom[0] != rowTo[0]) {
                                        MP.DragDrop.myThis.resize(rowFrom, spanClassDraggable, myUI, block);
                                    }
                                    clearTimeout(t);
                                }, 0);
                            }
                            break;
                    }

                    var droppableTimeout = null;
                    if (isNewBlock) {
                        droppableTimeout = setTimeout(function() {
                            MP.DragDrop.myThis.makeDroppable();
                            MP.Resizer.myThis.updateSplitterHeight(block, 'drop');
                            clearTimeout(droppableTimeout);
                        }, 0);
                    } else {
                        droppableTimeout = setTimeout(function() {
                            MP.DragDrop.myThis.resizer.updateResizableOptions(block, rowFrom, rowTo);
                            clearTimeout(droppableTimeout);
                        }, 0);
                    }

                },
                over: function() {
                    var wrapperId = $(this).attr('data-motopress-wrapper-id');
                    var wrapper = $('[data-motopress-wrapper-id="'+ wrapperId +'"][class*="span"]');
                    var wrapperOffset = wrapper.offset();
                    MP.DragDrop.myThis.wrapperHighlight.css({
                        'width': wrapper.width(),
                        'height': wrapper.height(),
                        'top': wrapperOffset.top,
                        'left': wrapperOffset.left
                    }).show();
                },
                out: function() {
                    MP.DragDrop.myThis.wrapperHighlight.hide();
                }
            });
        },

        canInsertLayoutWrapperContent: function(layoutWrapper, data) {
            var spanNumber = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(layoutWrapper.prop('class').split(' ')));
            var spanSum = 0;
            $(data).filter('.row:first').children('[class*="span"]').each(function() {
                spanSum += MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass($(this).prop('class').split(' ')));
            });
            var result = false;
            if(spanSum == spanNumber) {
                result = true;
            }
            return result;
        },

        canInsert: function(rowTo, ui) {
            var result = true;
            var spanLimit = 12;
            if (!rowTo.parent('.container').length) {
                spanLimit = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(rowTo.parent('[class*="span"]').prop('class').split(' ')));
            }

            var spanCount = 0;
            rowTo.children('[class*="span"]').each(function() {
                if (MP.DragDrop.myThis.notClone($(this), ui) && !$(this).hasClass('motopress-empty')) {
                    if ($(this).children('.row').length) {
                        spanCount += MP.DragDrop.myThis.calcSpanCount($(this), ui);
                    } else {
                        spanCount++;
                    }
                }
            });

            if(ui != null && this.isLayoutWrapper(ui.draggable)) {
                var freeSpace = spanLimit - spanCount;
                var wrapperSpanCount = this.calcSpanCount(ui.draggable, ui);
                if(freeSpace < wrapperSpanCount) result = false;
            } else {
                if(spanCount >= spanLimit) result = false;
            }
            if (!result) {
                parent.MP.Flash.setFlash(localStorage.getItem('blocksOverflow'), 'error');
                parent.MP.Flash.showMessage();
            }

            return result;
        },

        notClone: function(span, ui) {
            return (ui == null || (span[0] != ui.draggable[0] && span[0] != ui.helper[0])) ? true : false;
        },

        calcSpanCount: function(span, ui) {
            var maxSpanCount = 0;
            span.children('.row').each(function() {
                var spanCount = 0;
                $(this).children('[class*="span"]').each(function() {
                    if (MP.DragDrop.myThis.notClone($(this), ui) && !$(this).hasClass('motopress-empty')) {
                        if ($(this).children('.row').length) {
                            spanCount += MP.DragDrop.myThis.calcSpanCount($(this), ui);
                        } else {
                            spanCount++;
                        }
                    }
                });
                if (spanCount > maxSpanCount) maxSpanCount = spanCount;
            });
            return maxSpanCount;
        },

        resize: function(rowFrom, spanClassDraggable, ui, block) {
            //from
            var spanNumberDraggable = MP.DragDrop.getSpanNumber(spanClassDraggable);
            var spanNumberDraggableCopy = null;
            var oldSpanNumbers = [];
            var newSpanNumbers = [];
            var i = 0;
            var j = 0;

            if (rowFrom != null) {
                var siblingsFrom = rowFrom.children('[class*="span"]');
                siblingsFrom.each(function(index) {
                    if (MP.DragDrop.myThis.notClone($(this), ui)) {
                        oldSpanNumbers[index] = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass($(this).prop('class').split(' ')));
                    } else {
                        oldSpanNumbers[index] = null;
                    }
                });
                newSpanNumbers = oldSpanNumbers.slice(0);
                spanNumberDraggableCopy = spanNumberDraggable;

                if (oldSpanNumbers.length) {
                    while (spanNumberDraggableCopy != 0) {
                        if (j == 100) {
                            console.log('LOOPED IN `resize()` -> FROM');
                            break;
                        }
                        j++;
                        if (newSpanNumbers[i] != null) {
                            newSpanNumbers[i] += 1;
                            spanNumberDraggableCopy--;
                        }
                        i++;
                        if (i == newSpanNumbers.length) i = 0;
                    }

                    siblingsFrom.each(function(index) {
                        if (newSpanNumbers[index] != null) {
                            if (oldSpanNumbers[index] != newSpanNumbers[index]) {
                                $(this).removeClass('span'+oldSpanNumbers[index]).addClass('span'+newSpanNumbers[index]);
                                if ($(this).children('.row').length) {
                                    console.log('wrapper from');
                                    MP.DragDrop.myThis.recursiveResizeFrom($(this), oldSpanNumbers[index], newSpanNumbers[index], ui);
                                }
                            }
                        }
                    });
                }
            }

            //to
            var siblingsTo = block.siblings('[class*="span"]');
            var containerNumber = 12;
            var wrapper = block.parent('.row').parent('[class*="span"]');
            if (wrapper.children('.row').length) containerNumber = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(wrapper.prop('class').split(' ')));
            var newSpanNumber = Math.floor(containerNumber / (siblingsTo.length + 1));
            var maxSpanNumber = 0;
            oldSpanNumbers = [];
            newSpanNumbers = [];

            var hasEmptySpan = false;
            //var emptySpanNumberSum = 0;
            siblingsTo.each(function(index) {
                oldSpanNumbers[index] = {};
                var oldSpanNumber = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass($(this).prop('class').split(' ')));
                if ($(this).children('.row').length) {
                    oldSpanNumbers[index].type = 'wrapper';
                    oldSpanNumbers[index].max = oldSpanNumber - MP.DragDrop.myThis.calcSpanCount($(this), ui);
                } else if ($(this).hasClass('motopress-empty')) {
                    oldSpanNumbers[index].type = 'empty';
                    oldSpanNumbers[index].max = oldSpanNumber;
                    hasEmptySpan = true;
                    //emptySpanNumberSum += oldSpanNumber;
                } else {
                    oldSpanNumbers[index].type = 'simple';
                    if (oldSpanNumber > 1) {
                        oldSpanNumbers[index].max = oldSpanNumber - 1;
                    } else {
                        oldSpanNumbers[index].max = 0;
                    }
                }
                maxSpanNumber += oldSpanNumbers[index].max;
                oldSpanNumbers[index].span = oldSpanNumber;
                newSpanNumbers[index] = oldSpanNumber;
            });
            if (maxSpanNumber != 0 && newSpanNumber > maxSpanNumber) newSpanNumber = maxSpanNumber;
            if (newSpanNumber == 0 && hasEmptySpan) newSpanNumber = 1;
            block.removeClass(spanClassDraggable).addClass('span'+newSpanNumber);

            if(this.isLayoutWrapper(block)) {
                if(spanNumberDraggable < newSpanNumber) {
                    MP.DragDrop.myThis.recursiveResizeFrom(block, spanNumberDraggable, newSpanNumber, ui);
                } else if(spanNumberDraggable > newSpanNumber) {
                    MP.DragDrop.myThis.recursiveResizeTo(block, spanNumberDraggable, newSpanNumber, ui);
                }
            }

            if (siblingsTo.length) {
                i = 0;
                j = 0;
                while (newSpanNumber != 0) {
                    if (j == 100) {
                        //console.log('LOOPED IN \'resize()\' -> TO');
                        break;
                    }
                    j++;
                    if ((newSpanNumbers[i] > 1 && oldSpanNumbers[i].max) || (oldSpanNumbers[i].type == 'empty' && oldSpanNumbers[i].max >= 0)) {
                        newSpanNumbers[i]--;
                        oldSpanNumbers[i].max--;
                        newSpanNumber--;
                    }
                    i++;
                    if (i == newSpanNumbers.length) i = 0;
                }
                siblingsTo.each(function(index) {
                    if (newSpanNumbers[index] == 0) {
                        $(this).remove();
                    } else if (oldSpanNumbers[index].span != newSpanNumbers[index]) {
                        $(this).removeClass('span'+oldSpanNumbers[index].span).addClass('span'+newSpanNumbers[index]);
                        if ($(this).children('.row').length) {
                            //console.log('wrapper to');
                            MP.DragDrop.myThis.recursiveResizeTo($(this), oldSpanNumbers[index].span, newSpanNumbers[index], ui);
                        }
                    }
                });
            }
        },

        recursiveResizeFrom: function(wrapper, oldSpanNumber, newSpanNumber, ui) {
            var space = newSpanNumber - oldSpanNumber;

            wrapper.children('.row').each(function() {
                var oldSpanNumbers = [];
                var newSpanNumbers = [];
                var i = 0;
                var j = 0;

                var spans = $(this).children('[class*="span"]');
                spans.each(function(index) {
                    if (MP.DragDrop.myThis.notClone($(this), ui)) {
                        oldSpanNumbers[index] = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass($(this).prop('class').split(' ')));
                    } else {
                        oldSpanNumbers[index] = null;
                    }
                });
                newSpanNumbers = oldSpanNumbers.slice(0);
                var spaceCopy = space;

                var resizeFlag = true;
                if ((oldSpanNumbers.length == 1 && oldSpanNumbers[0] == null) || !oldSpanNumbers.length) resizeFlag = false;

                if (resizeFlag) {
                    while (spaceCopy != 0) {
                        if (j == 100) {
                            //console.log('LOOPED IN \'recursiveResizeFrom()\'');
                            break;
                        }
                        j++;
                        if (newSpanNumbers[i] != null) {
                            newSpanNumbers[i] += 1;
                            spaceCopy--;
                        }
                        i++;
                        if (i == newSpanNumbers.length) i = 0;
                    }

                    spans.each(function(index) {
                        if (newSpanNumbers[index] != null) {
                            if (oldSpanNumbers[index] != newSpanNumbers[index]) {
                                $(this).removeClass('span'+oldSpanNumbers[index]).addClass('span'+newSpanNumbers[index]);
                                if ($(this).children('.row').length) {
                                    //console.log('wrapper from');
                                    MP.DragDrop.myThis.recursiveResizeFrom($(this), oldSpanNumbers[index], newSpanNumbers[index], ui);
                                }
                            }
                        }
                    });
                }
            });
        },

        recursiveResizeTo: function(wrapper, oldSpanNumber, newSpanNumber, ui) {
            var space = oldSpanNumber - newSpanNumber;

            wrapper.children('.row').each(function() {
                //var maxSpanNumber = 0;
                var oldSpanNumbers = [];
                var newSpanNumbers = [];
                var i = 0;
                var j = 0;
                var spans = $(this).children('[class*="span"]');
                var hasEmptySpan = false;

                spans.each(function(index) {
                    if (MP.DragDrop.myThis.notClone($(this), ui)) {
                        oldSpanNumbers[index] = {};
                        var oldSpanNumb = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass($(this).prop('class').split(' ')));
                        if ($(this).children('.row').length) {
                            oldSpanNumbers[index].type = 'wrapper';
                            oldSpanNumbers[index].max = oldSpanNumb - MP.DragDrop.myThis.calcSpanCount($(this), ui);
                        } else if ($(this).hasClass('motopress-empty')) {
                            oldSpanNumbers[index].type = 'empty';
                            oldSpanNumbers[index].max = oldSpanNumb;
                            hasEmptySpan = true;
                        } else {
                            oldSpanNumbers[index].type = 'simple';
                            if (oldSpanNumb > 1) {
                                oldSpanNumbers[index].max = oldSpanNumb - 1;
                            } else {
                                oldSpanNumbers[index].max = 0;
                            }
                        }
                        //maxSpanNumber += oldSpanNumbers[index].max;
                        oldSpanNumbers[index].span = oldSpanNumb;
                        newSpanNumbers[index] = oldSpanNumb;
                    } else {
                        oldSpanNumbers[index].span = null;
                        oldSpanNumbers[index].max = 0;
                        newSpanNumbers[index] = null;
                    }
                });
                var spaceCopy = space;
                //if (maxSpanNumber != 0 && spaceCopy > maxSpanNumber) spaceCopy = maxSpanNumber;
                if (spaceCopy == 0 && hasEmptySpan) spaceCopy = 1;
                while (spaceCopy != 0) {
                    if (j == 100) {
                        //console.log('LOOPED IN `recursiveResizeTo()`');
                        break;
                    }
                    j++;
                    if ((newSpanNumbers[i] > 1 && oldSpanNumbers[i].span != null && oldSpanNumbers[i].max) || (oldSpanNumbers[i].type == 'empty' && oldSpanNumbers[i].max > 0)) {
                        newSpanNumbers[i]--;
                        oldSpanNumbers[i].max--;
                        spaceCopy--;
                    }
                    i++;
                    if (i == newSpanNumbers.length) i = 0;
                }

                spans.each(function(index) {
                    if (newSpanNumbers[index] == 0) {
                        $(this).remove();
                    } else if (oldSpanNumbers[index].span != null) {
                        if (oldSpanNumbers[index].span != newSpanNumbers[index]) {
                            $(this).removeClass('span'+oldSpanNumbers[index].span).addClass('span'+newSpanNumbers[index]);
                            if ($(this).children('.row').length) {
                                //console.log('wrapper to');
                                MP.DragDrop.myThis.recursiveResizeTo($(this), oldSpanNumbers[index].span, newSpanNumbers[index], ui);
                            }
                        }
                    }
                });
            });
        },

        isLayoutWrapper: function(span) {
            return (typeof span.attr('data-motopress-wrapper-type') != 'undefined') ? true : false;
        },

        isLoopExist: function() {
            return ($('.container:not(#motopress-grid) [class*="span"][data-motopress-type="loop"]').length) ? true : false;
        },

        getList: function() {
            $.ajax({
                url: parent.motopress.ajaxUrl,
                data: {
                    action: 'motopress_get_list',
                    nonce: parent.motopress.nonces.motopress_get_list
                },
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    MP.DragDrop.myThis.sidebarList = data.sidebar_list;
                    MP.DragDrop.myThis.headerList = data.header_list;
                    MP.DragDrop.myThis.footerList = data.footer_list;
                    MP.DragDrop.myThis.staticBlockList = data.static_list;
                    MP.DragDrop.myThis.loopBlockList = data.loop_list;
                    MP.DragDrop.myThis.main();

                    if (parent.MP.Navbar.myThis.currentViewMode !== 'editor') { //fix grid
                        parent.MP.Navbar.myThis.showIframe('preview');
                    }

                    parent.MP.Navbar.myThis.preload(false);
                    //parent.MP.Navbar.myThis.showWelcomeWindow();
                },
                error: function() {
                    parent.MP.Flash.setFlash(localStorage.getItem('listError'), 'error');
                    parent.MP.Navbar.myThis.preload(false);
                }
            });
        },

        getLoop: function(blockContent, loopId) {
            parent.MP.Navbar.myThis.preload(true);
            var obj = parent.MP.Iframe.contents.find('html').clone();
            parent.MP.Navbar.myThis.clearHelpers(obj);
            parent.MP.Navbar.myThis.hideHiddenBlocks(obj, false);
            parent.MP.Navbar.myThis.clearParams(obj);
            var data = obj.find('body div#motopress-main').html();
            var selected = parent.MP.Navbar.myThis.element.find('option:selected');

            $.ajax({
                url: parent.motopress.ajaxUrl,
                data: {
                    action: 'motopress_get_loop',
                    nonce: parent.motopress.nonces.motopress_get_loop,

                    link: selected.val(),
                    page: selected.attr('data-template'),
                    data: data
                },
                dataType: 'html',
                type: 'POST',
                success: function(data) {
                    var loop = $(data).find('[class*="span"][data-motopress-type="loop"][data-motopress-id="'+loopId+'"]').html();
                    blockContent.html(loop);
                    MP.Resizer.myThis.updateSplitterHeight(blockContent.parent(), 'edit');
                    parent.MP.Navbar.myThis.preload(false);
                },
                error: function(jqXHR) {
                    var error = JSON.parse(jqXHR.responseText);
                    if (error.debug) {
                        console.log(error.message);
                    } else {
                        parent.MP.Flash.setFlash(error.message, 'error');
                        parent.MP.Flash.showMessage();
                    }
                    parent.MP.Navbar.myThis.preload(false);
                }
            });
        }
    })
});
