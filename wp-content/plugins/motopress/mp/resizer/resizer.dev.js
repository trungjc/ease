steal('jquery/class', function($) {
   /**
    * @class MP.Resizer
    */
    $.Class("MP.Resizer",
    /** @Static */
    {
        myThis: null
    },
    /** @Prototype */
    {
        minHeight: 50,
        handle: null,
        addedStyleProperties: ['position', 'top', 'left', 'width', 'height', 'background-position', 'background-repeat'],
        emptySpan: $('<div />', {
            'class': 'motopress-empty'
        }),

        splitter: $('<div />', {
            'class': 'motopress-splitter'
        }),

        init: function() {
            MP.Resizer.myThis = this;
        },

        makeResizable: function(obj) {
            obj.not("[data-motopress-wrapper-id]").resizable({
                grid: [MP.Grid.myThis.columnWithMargin, 10],
                handles: 'e, s, w, se, sw',
                helper: 'motopress-resizer-helper',
                minWidth: MP.Grid.myThis.columnWidth,
                minHeight: MP.Resizer.myThis.minHeight,
                zIndex: 1002,

                create: function() {
                    $(this).resizable('option', 'maxWidth', $(this).width());
                },

                start: function(e, ui) {
                    //handle = $(e.srcElement);
                    MP.Resizer.myThis.handle = $(e.originalEvent.target);
                    ui.element.css({
                        'position': 'relative',
                        'top': 0,
                        'left': 0
                    });
                },

                stop: function(e, ui) {
                    var handleClass = MP.Resizer.myThis.getHandleClass(MP.Resizer.myThis.handle.prop('class').split(' '));
                    switch(handleClass) {
                        case 'ui-resizable-s':
                            MP.Resizer.myThis.verticalResize(ui);
                            break;

                        case 'ui-resizable-w':
                            MP.Resizer.myThis.horizontalResize(handleClass, ui);
                            break;

                        case 'ui-resizable-e':
                            MP.Resizer.myThis.horizontalResize(handleClass, ui);
                            break;

                        case 'ui-resizable-sw':
                            //console.group('ui-resizable-sw');
                            MP.Resizer.myThis.verticalResize(ui);
                            MP.Resizer.myThis.horizontalResize(handleClass, ui);
                            //console.groupEnd();
                            break;

                        case 'ui-resizable-se':
                            //console.group('ui-resizable-se');
                            MP.Resizer.myThis.verticalResize(ui);
                            MP.Resizer.myThis.horizontalResize(handleClass, ui);
                            //console.groupEnd();
                            break;
                    }

                    var resetAddedStyleProperties = {};
                    for (var i = 0; i < MP.Resizer.myThis.addedStyleProperties.length; i++) {
                        resetAddedStyleProperties[MP.Resizer.myThis.addedStyleProperties[i]] = '';
                    }
                    ui.element.css(resetAddedStyleProperties);

                    MP.Resizer.myThis.updateSplitterHeight(obj, 'resize');
                }
            });
        },

        horizontalResize: function(handleClass, ui) {
            //var logTitle = null;
            //if(handleClass == 'ui-resizable-w' || handleClass == 'ui-resizable-sw') {
                //logTitle = 'ui-resizable-w';
            //} else if(handleClass == 'ui-resizable-e' || handleClass == 'ui-resizable-se') {
                //logTitle = 'ui-resizable-e';
            //}
            //console.group(logTitle);

            var originalSpanWidth = ui.originalSize.width;
            //console.log('originalSpanWidth = '+originalSpanWidth);
            var originalSpanClass = MP.DragDrop.getSpanClass(ui.element.prop('class').split(' '));
            var originalSpanNumber = MP.DragDrop.getSpanNumber(originalSpanClass);
            //console.log('originalSpanNumber = '+originalSpanNumber);

            var newSpanWidth = (ui.element.width() % 10 == 0) ? ui.element.width() : ui.helper.width();
            //ui.element.width(); // 1.7.2
            //ui.helper.width(); // 1.10.0
            //console.log('newSpanWidth = '+newSpanWidth);
            var newSpanNumber = Math.ceil(newSpanWidth / MP.Grid.myThis.columnWithMargin);
            //console.log('newSpanNumber = '+newSpanNumber);

            var diff = Math.abs(originalSpanNumber - newSpanNumber);
            //console.log('diff = '+diff);

            if(originalSpanWidth > newSpanWidth) {
                //console.log('constrict');
                ui.element.removeClass(originalSpanClass).addClass('span' + newSpanNumber);

                var direction = null;
                if(handleClass == 'ui-resizable-w' || handleClass == 'ui-resizable-sw') {
                    direction = 'west';
                } else if(handleClass == 'ui-resizable-e' || handleClass == 'ui-resizable-se') {
                    direction = 'east';
                }

                var empty = null;
                if(direction == 'west') {
                    empty = ui.element.prev('.motopress-empty');
                } else if(direction == 'east') {
                    empty = ui.element.next('.motopress-empty');
                }
                if(empty.length) {
                    //console.log('.empty exists');
                    var originalEmptyClass = MP.DragDrop.getSpanClass(empty.prop('class').split(' '));
                    //console.log('originalEmptyClass = '+originalEmptyClass);
                    var originalEmptyNumber = MP.DragDrop.getSpanNumber(originalEmptyClass);
                    //console.log('originalEmptyNumber = '+originalEmptyNumber);
                    var newEmptyNumber = originalEmptyNumber + diff;
                    //console.log('newEmptyNumber = '+newEmptyNumber);
                    empty.removeClass(originalEmptyClass).addClass('span' + newEmptyNumber);
                } else {
                    //console.log('.empty not exists');
                    var emptySpanClone = MP.Resizer.myThis.emptySpan.clone();
                    MP.DragDrop.myThis.makeEditableEmptySpan(emptySpanClone);
                    if(direction == 'west') {
                        ui.element.before(emptySpanClone.addClass('span' + diff));
                    } else if(direction == 'east') {
                        ui.element.after(emptySpanClone.addClass('span' + diff));
                    }
                }
                ui.element.resizable('option', 'maxWidth', newSpanWidth);
            }
            //console.groupEnd();
        },

        verticalResize: function(ui) {
            //console.group('ui-resizable-s');
            //var originalSpanHeight = ui.originalSize.height;
            //console.log('originalSpanHeight = '+originalSpanHeight);

            var minHeight = parseInt(ui.element.css('min-height'));
            //console.log('minHeight = '+minHeight);
            if(minHeight == ui.element.height()) ui.element.css('min-height', '');

            var newSpanHeight = ui.element.height();
            //console.log('newSpanHeight = '+newSpanHeight);

            ui.element.css('min-height', newSpanHeight);
            //console.groupEnd();
        },

        makeSplittable: function(obj) {
            var splitterStartPos = null;
            var splitterEndPos = null;


            obj.find('.motopress-splitter').draggable({
                axis: 'x',
                cursor: 'col-resize',
                grid: [MP.Grid.myThis.columnWithMargin, 0],
                helper: 'clone',
                zIndex: 1,

                start: function(e, ui) {
                    splitterStartPos = ui.originalPosition.left;
                    splitterEndPos = splitterStartPos;
//                    $('.container:not(#motopress-grid) [class*="span"]:not([data-motopress-wrapper-type])>.motopress-helper motopress-drag-handle');

                    ui.helper.closest('.row').find('.motopress-drag-handle').css('cursor', 'col-resize');
                },

                stop: function(e, ui) {
                    ui.helper.closest('.row').find('.motopress-drag-handle').css('cursor', 'move');

                    splitterEndPos = ui.position.left;
                    if ((splitterStartPos > 0 && splitterStartPos < MP.Grid.myThis.columnWithMargin) || (splitterStartPos < 0 && splitterStartPos > -MP.Grid.myThis.columnWithMargin)) {
                        splitterStartPos = 0;
                    }

                    var diff = splitterStartPos - splitterEndPos;

                    var currentBlock = $(this).closest('[class*="span"]');
                    var nextBlock = currentBlock.prev('[class*="span"]');

                    var curBlockOldSpan = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(currentBlock.prop('class').split(' ')));
                    var nextBlockOldSpan = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(nextBlock.prop('class').split(' ')));
                    var curBlockNewSpan = curBlockOldSpan;
                    var nextBlockNewSpan = nextBlockOldSpan;
                    //console.log('curBlockOldSpan = ' + curBlockOldSpan);
                    //console.log('nextBlockOldSpan = ' + nextBlockOldSpan);

                    var diffInSpan = Math.abs(diff / MP.Grid.myThis.columnWithMargin);
                    var curDiffInSpan = diffInSpan;
                    var nextDiffInSpan = diffInSpan;
                    //console.log('diffInSpan = ' + diffInSpan);

                    // to right
                    if (diff < 0) {
                        var oldCurrentBlockSpan = null;
                        if (curDiffInSpan >= curBlockOldSpan) {
                            if (currentBlock.hasClass('motopress-empty')) {
                                var oldCurrentBlock = currentBlock;
                                oldCurrentBlockSpan = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(oldCurrentBlock.prop('class').split(' ')));
                                currentBlock = currentBlock.next();
                                oldCurrentBlock.remove();

                                if (currentBlock.length) {
                                    curBlockOldSpan = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(currentBlock.prop('class').split(' ')));
                                    curBlockNewSpan = curBlockOldSpan;
                                    curDiffInSpan = diffInSpan - oldCurrentBlockSpan;
                                }
                            } else {
                                curDiffInSpan = curBlockOldSpan - 1;
                                nextDiffInSpan = curBlockOldSpan - 1;
                            }
                        }

                        if (currentBlock.length) {
                            if (curDiffInSpan >= curBlockOldSpan) {
                                curDiffInSpan = curBlockOldSpan - 1;
                                if (oldCurrentBlockSpan) {
                                    nextDiffInSpan = diffInSpan - (diffInSpan - (oldCurrentBlockSpan + curBlockOldSpan)) - 1;
                                } else {
                                    nextDiffInSpan = curBlockOldSpan - 1;
                                }
                            }
                            curBlockNewSpan -= curDiffInSpan;
                            nextBlockNewSpan += nextDiffInSpan;

                            if (currentBlock.children('.row').length) {
                                var curBlockMinSpan = MP.DragDrop.myThis.calcSpanCount(currentBlock, null);
                                if (curBlockNewSpan < curBlockMinSpan) {
                                    nextBlockNewSpan -= curBlockMinSpan - curBlockNewSpan;
                                    curBlockNewSpan = curBlockMinSpan;
                                }
                                MP.DragDrop.myThis.recursiveResizeTo(currentBlock, curBlockOldSpan, curBlockNewSpan, null);
                            }
                            if (nextBlock.children('.row').length) {
                                MP.DragDrop.myThis.recursiveResizeFrom(nextBlock, nextBlockOldSpan, nextBlockNewSpan, null);
                            }

                            currentBlock.removeClass('span'+curBlockOldSpan).addClass('span'+curBlockNewSpan);
                            nextBlock.removeClass('span'+nextBlockOldSpan).addClass('span'+nextBlockNewSpan);
                        } else {
                            nextDiffInSpan = oldCurrentBlockSpan;
                            nextBlockNewSpan += nextDiffInSpan;

                            if (nextBlock.children('.row').length) {
                                MP.DragDrop.myThis.recursiveResizeFrom(nextBlock, nextBlockOldSpan, nextBlockNewSpan, null);
                            }

                            nextBlock.removeClass('span'+nextBlockOldSpan).addClass('span'+nextBlockNewSpan);
                        }
                    }

                    // to left
                    else if (diff > 0) {
                        var oldNextBlockSpan = null;
                        if (nextDiffInSpan >= nextBlockOldSpan) {
                            if (nextBlock.hasClass('motopress-empty')) {
                                var oldNextBlock = nextBlock;
                                oldNextBlockSpan = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(oldNextBlock.prop('class').split(' ')));
                                nextBlock = nextBlock.prev();
                                oldNextBlock.remove();

                                if (nextBlock.length) {
                                    nextBlockOldSpan = MP.DragDrop.getSpanNumber(MP.DragDrop.getSpanClass(nextBlock.prop('class').split(' ')));
                                    nextBlockNewSpan = nextBlockOldSpan;
                                    nextDiffInSpan = diffInSpan - oldNextBlockSpan;
                                }
                            } else {
                                curDiffInSpan = nextBlockOldSpan - 1;
                                nextDiffInSpan = nextBlockOldSpan - 1;
                            }
                        }

                        if (nextBlock.length) {
                            if (nextDiffInSpan >= nextBlockOldSpan) {
                                nextDiffInSpan = nextBlockOldSpan - 1;
                                if (oldNextBlockSpan) {
                                    curDiffInSpan = diffInSpan - (diffInSpan - (oldNextBlockSpan + nextBlockOldSpan)) - 1;
                                } else {
                                    curDiffInSpan = curBlockOldSpan - 1;
                                }
                            }
                            curBlockNewSpan += curDiffInSpan;
                            nextBlockNewSpan -= nextDiffInSpan;

                            if (nextBlock.children('.row').length) {
                                var nextBlockMinSpan = MP.DragDrop.myThis.calcSpanCount(nextBlock, null);
                                if (nextBlockNewSpan < nextBlockMinSpan) {
                                    curBlockNewSpan -= nextBlockMinSpan - nextBlockNewSpan;
                                    nextBlockNewSpan = nextBlockMinSpan;
                                }
                                MP.DragDrop.myThis.recursiveResizeTo(nextBlock, nextBlockOldSpan, nextBlockNewSpan, null);
                            }
                            if (currentBlock.children('.row').length) {
                                MP.DragDrop.myThis.recursiveResizeFrom(currentBlock, curBlockOldSpan, curBlockNewSpan, null);
                            }

                            currentBlock.removeClass('span'+curBlockOldSpan).addClass('span'+curBlockNewSpan);
                            nextBlock.removeClass('span'+nextBlockOldSpan).addClass('span'+nextBlockNewSpan);
                        } else {
                            curDiffInSpan = oldNextBlockSpan;
                            curBlockNewSpan += curDiffInSpan;

                            if (currentBlock.children('.row').length) {
                                MP.DragDrop.myThis.recursiveResizeFrom(currentBlock, curBlockOldSpan, curBlockNewSpan, null);
                            }

                            currentBlock.removeClass('span'+curBlockOldSpan).addClass('span'+curBlockNewSpan);
                        }
                    }

                    if (!currentBlock.children('.row').length) {
                        MP.Resizer.myThis.updateResizableOptions(currentBlock, null, null);
                    } else {
                        currentBlock.find('[class*="span"][data-motopress-type]:not(.motopress-empty, [data-motopress-wrapper-id])').each(function() {
                            MP.Resizer.myThis.updateResizableOptions($(this), null, null);
                        });
                    }
                    if (!nextBlock.children('.row').length) {
                        MP.Resizer.myThis.updateResizableOptions(nextBlock, null, null);
                    } else {
                        nextBlock.find('[class*="span"][data-motopress-type]:not(.motopress-empty, [data-motopress-wrapper-id])').each(function() {
                            MP.Resizer.myThis.updateResizableOptions($(this), null, null);
                        });
                    }
                    MP.Resizer.myThis.updateSplitterHeight(currentBlock, 'split');
                }
            });
        },

        updateResizableOptions: function(draggableBlock, rowFrom, rowTo) {
            if (draggableBlock && !draggableBlock.hasClass('motopress-empty')) {
                draggableBlock.resizable('option', 'maxWidth', draggableBlock.width());
            }
            if (rowFrom) {
                rowFrom.find('[class*="span"]').each(function() {
                    if (!$(this).children('.row').length && !$(this).hasClass('motopress-empty') && $(this).is('[data-motopress-type]')) {
                        $(this).resizable('option', 'maxWidth', $(this).width());
                    }
                });
            }
            if (rowTo) {
                rowTo.find('[class*="span"]').each(function() {
                    if (!$(this).children('.row').length && !$(this).hasClass('motopress-empty') && $(this).is('[data-motopress-type]')) {
                        $(this).resizable('option', 'maxWidth', $(this).width());
                    }
                });
            }
        },

        getHandleClass: function(classes) {
            var expr = new RegExp('^ui-resizable-(e|s|w|se|sw)$', 'ig');
            var handleClass = null;
            for(var i = 0; i < classes.length; i++) {
                if (expr.test(classes[i])) {
                    handleClass = classes[i];
                }
            }
            return handleClass;
        },

        updateSplitterHeight: function(obj, action) {
            var t = setTimeout(function () {
                var necessaryRow = null;
                if (action === 'init') {
                    necessaryRow = $('.container:not(#motopress-grid) .row');
                    $.each(necessaryRow, function() {
                        $(this).find('[class*="span"] > .motopress-helper > .motopress-splitter').height($(this).height());
                    });
                } else if (action === 'drag' || action === 'drop' || action === 'split' || action === 'resize' || action === 'edit') {
                    if (obj !== null && obj.length && obj.length === 1) {
                        necessaryRow = obj.parents('.row').eq(-2);
                        $.each($.merge(necessaryRow, necessaryRow.find('.row')), function() {
                            $(this).find('[class*="span"] > .motopress-helper > .motopress-splitter').height($(this).height());
                        });
                    }
                } else if (action === 'remove') {
                    if (obj !== null && obj.length) {
                        $.each($.merge(obj, obj.find('.row')), function() {
                            $(this).find('[class*="span"] > .motopress-helper > .motopress-splitter').height($(this).height());
                        });
                    }
                }
                clearTimeout(t);
            }, 0);
        }

    })
});
