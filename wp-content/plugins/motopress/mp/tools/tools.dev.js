steal( 'jquery/class', function($) {
   /**
    * @class MP.Tools
    */
    $.Class('MP.Tools',
    /** @Static */
    {
        myThis: null
    },
    /** @Prototype */
    {
        removeBlockBtn: $('<div />', {
            'class': 'motopress-remove-block-btn motopress-default motopress-icon-trash',
            title: localStorage.getItem('remove')
        }),
        editContentBtn: $('<div />', {
            'class': 'motopress-edit-content-btn motopress-default motopress-icon-pencil',
            title: localStorage.getItem('edit')
        }),
        contentSelect: $('<select />', {
            'class': 'motopress-content-select',
            id: 'motopress-content-select',
            title: 'Select content'
        }),

        contentOption: $('<option />'),

        tools: $('<div />', {
            'class': 'motopress-tools motopress-default'
        }),
        toolsInner: $('<div />', {
            'class' : 'motopress-tools-inner motopress-default'
        }),
        //layout wrapper tools
        dragLayoutWrapperHandle: $('<div />', {
            'class': 'motopress-drag-layout-wrapper-handle icon-move',
            title: 'Move wrapper'
        }),
        contentLayoutWrapperBtn: $('<div />', {
            'class': 'motopress-content-layout-wrapper-btn icon-file',
            title: 'Select content'
        }),
        /* popover
        popover: $('<div />', {
            'class': 'popover fade right in'
        }),
        arrow: $('<div />', {
            'class': 'arrow'
        }),
        popoverInner: $('<div />', {
            'class': 'popover-inner'
        }),
        popoverTitle: $('<h3 />', {
            'class': 'popover-title'
        }),
        popoverContent: $('<div />', {
            'class': 'popover-content'
        }),
        */

        /*
        removeLayoutWrapperBtn: $('<div />', {
            'class': 'motopress-remove-layout-wrapper-btn icon-trash motopress-default',
            title: 'Remove wrapper'
        }),
        */

        layoutWrapperTools: $('<div />', {
            'class': 'motopress-layout-wrapper-tools motopress-default'
        }),

        //usedWrapperList: {},

        setup: function() {
            //this.removeBlockBtn.append(this.removeIcon);
            this.toolsInner.append(this.removeBlockBtn);
            //this.editContentBtn.append(this.editIcon);
            this.tools.append(this.toolsInner);
            //this.popoverInner.append(this.popoverTitle, this.popoverContent);
            //this.popover.append(this.arrow, this.popoverInner);
            //this.layoutWrapperTools.append(this.dragLayoutWrapperHandle, this.contentLayoutWrapperBtn, this.popover, this.removeLayoutWrapperBtn);
            //this.layoutWrapperTools.append(this.contentLayoutWrapperBtn, this.popover);
        },

        init: function() {
            MP.Tools.myThis = this;
        },

        makeRemovable: function(obj) {
            var btn = obj.children('.motopress-helper').children('.motopress-tools').children('.motopress-tools-inner')
                         .children('.motopress-remove-block-btn');
            btn.on('click', function() {
                var spanRemovable = $(this).closest('[class*="span"]');

                var rowForSplitterCalc = spanRemovable.parents('.row').eq(-2);

                var spanClassRemovable = MP.DragDrop.getSpanClass(spanRemovable.prop('class').split(' '));
                var rowFrom = spanRemovable.parent('.row');

                spanClassRemovable = MP.DragDrop.myThis.removeEmptyBlocks(spanRemovable, spanClassRemovable);

                spanRemovable.children('.motopress-helper').children('.motopress-tools').children('.motopress-tools-inner').children().tooltip('hide');

                if (spanRemovable.attr('data-motopress-type') == 'loop') {
                    var loopBlock = MP.LeftMenu.myThis.leftMenu.find('.motopress-new-block[data-type="loop"]');
                    loopBlock.draggable('enable');
                }

                spanRemovable.remove();

                rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);

                MP.DragDrop.myThis.resize(rowFrom, spanClassRemovable, null, spanRemovable);

                MP.DragDrop.myThis.resizer.updateResizableOptions(null, rowFrom, null);

                MP.Resizer.myThis.updateSplitterHeight(rowForSplitterCalc, 'remove');
            });
        },

        makeHoverBlock: function(obj) {
            obj.hover(function() {
                if (!$('.bootstrap-select.open').length) {
                    $(this).children('.motopress-helper').children('.motopress-tools')
                           .children('.motopress-tools-inner').css({
                        zIndex: 1003
                    });
                }
            }, function() {
                if (!$(this).children('.motopress-helper').children('.motopress-tools')
                            .children('.motopress-tools-inner').find('.bootstrap-select.open').length) {
                    $(this).children('.motopress-helper').children('.motopress-tools').children('.motopress-tools-inner').css({
                        zIndex: 1002
                    });
                }
            });
        },

        makeHoverBlockTools: function(obj) {
            obj.children('.motopress-helper').children('.motopress-tools')
               .children('.motopress-tools-inner').children()
                .hover(function() {
                    $(this).css({color: 'red'});
                }, function() {
                    $(this).css({color: '#333'});
                })
                .css('cursor', 'pointer');
        },

        makeTooltipBlockTools: function(obj) {
            obj.children('.motopress-helper').children('.motopress-tools')
               .children('.motopress-tools-inner')
               .children('.motopress-remove-block-btn, .motopress-edit-content-btn').tooltip({
                placement: 'top',
                container: '.motopress-helper-container'
            });
        },
        makePopoverLoopBlockTools: function(obj) {
            var message = localStorage.getItem("editLoopMessage");
            var trans = {
                "%link%": "<a href='"+parent.MP.Settings.adminUrl+"edit.php' target='_blank'>",
                "%link1%": "<a href='"+parent.MP.Settings.adminUrl+"edit.php?post_type=page' target='_blank'>",
                "%endlink%": "</a>",
                "%endlink1%": "</a>",
            };
            var selector = obj.children('.motopress-helper').children('.motopress-tools')
               .children('.motopress-tools-inner').children('.motopress-edit-content-btn');
            selector.clickover({
                placement: function() {
                    return MP.Tools.myThis.calcPopoverPlacement(obj);
                },
                html: 'true',
                trigger: 'manual',
                title: localStorage.getItem("editLoop"),
                content: parent.MP.Utils.strtr(message, trans)
//                content: message
            });
        },
        makePopoverSidebarBlockTools: function(obj) {
            var message = localStorage.getItem("editSidebarMessage");
            var trans = {"%link%":"<a href='"+parent.MP.Settings.adminUrl+"widgets.php' target='_blank'>", "%endlink%":"</a>"};
            obj.children('.motopress-helper').children('.motopress-tools')
               .children('.motopress-tools-inner').children('.motopress-edit-content-btn').clickover({
                   placement: function() {
                       return MP.Tools.myThis.calcPopoverPlacement(obj);
                   },
                   html: 'true',
                   trigger: 'manual',
                   title: localStorage.getItem("editSidebar"),
                   content: parent.MP.Utils.strtr(message, trans)
//                   content: message
               });
        },
        makePopoverStaticBlockTools: function(obj) {
            var message = localStorage.getItem("editStaticMessage");
            var selector = obj.children('.motopress-helper').children('.motopress-tools')
               .children('.motopress-tools-inner').children('.motopress-edit-content-btn');
            selector.clickover({
                placement: function() {
                    return MP.Tools.myThis.calcPopoverPlacement(obj);
                },
                html: 'true',
                trigger: 'manual',
                title: localStorage.getItem("editStatic"),
                content: message
            });
        },
        calcPopoverPlacement: function(span) {
            var placement = 'bottom';
            if (span.siblings('[class*="span"]').length == 0) {
                placement = 'left';
            } else {
                if (!span.prev('[class*="span"]').length && span.hasClass('span1')) {
                    placement = 'right';
                } else if (!span.next('[class*="span"]').length) {
                    placement = 'left';
                }
            }
            return placement;
        },
        makeTypeStaticBlockTools: function(obj) {
            if(MP.DragDrop.myThis.staticBlockList != null) {
                var select = this.contentSelect.clone();
                var defaultOption = this.contentOption.clone();
                defaultOption.prop({
                    'value': 'default',
                    'selected': true,
                    'disabled': true,
                    'text': localStorage.getItem('selectContent')
                }).prependTo(select);
                $.each(MP.DragDrop.myThis.staticBlockList, function(index, value) {
                    var option = MP.Tools.myThis.contentOption.clone();
                    option.prop({
                        value: 'static/' + index,
                        text: value
                    }).appendTo(select);
                });
                obj.children('.motopress-helper').children('.motopress-tools')
                   .children('.motopress-tools-inner').append(select);

                var staticFile = obj.attr('data-motopress-static-file');
                if(staticFile) {
                    select.find('[value="' + staticFile + '"]').prop('selected', true);
                }

                this.changeStaticBlockType(select);

                this.makeSelectpicker(select);
            }
        },

        makeTypeLoopBlockTools: function(obj) {
            if (MP.DragDrop.myThis.loopBlockList != null) {
                var select = this.contentSelect.clone();
                var defaultOption = this.contentOption.clone();
                defaultOption.prop({
                    'value': 'default',
                    'selected': true,
                    'disabled': true,
                    'text': localStorage.getItem('selectContent')
                }).prependTo(select);
                $.each(MP.DragDrop.myThis.loopBlockList, function(index, value) {
                    var option = MP.Tools.myThis.contentOption.clone();
                    option.prop({
                        value: 'loop/' + index,
                        text: value
                    }).appendTo(select);
                });
                obj.children('.motopress-helper').children('.motopress-tools')
                   .children('.motopress-tools-inner').append(select);

                var loopFile = obj.attr('data-motopress-loop-file');
                if(loopFile) {
                    select.find('[value="' + loopFile + '"]').prop('selected', true);
                }

                this.changeLoopBlockType(select);

                this.makeSelectpicker(select);
            }
        },
        editSidebarContent: function(obj) {
            var tools = obj.children('.motopress-helper').children('.motopress-tools').children('.motopress-tools-inner');
            tools.prepend(this.editContentBtn.clone());
        },
        editLoopContent: function(obj) {
            var tools = obj.children('.motopress-helper').children('.motopress-tools').children('.motopress-tools-inner');
            tools.prepend(this.editContentBtn.clone());
        },
        editStaticContent: function(obj) {
            var tools = obj.children('.motopress-helper').children('.motopress-tools').children('.motopress-tools-inner');
            tools.prepend(this.editContentBtn.clone());

            var btn = tools.children('.motopress-edit-content-btn');
            btn.on('click', function() {
                parent.MP.Navbar.myThis.preload(true);
                btn.tooltip('hide');
                var select = $(this).nextAll('select.motopress-content-select');
                var selected = select.find('option:selected');
                var staticFile = selected.val();
                var staticName = (staticFile !== 'default') ? selected.text() : '';
                var editingSpan = select.closest('[class*="span"][data-motopress-type="static"]');
                editingSpan.attr('data-motopress-editing', 1);

                $.ajax({
                    url: parent.motopress.ajaxUrl,
                    data: {
                        action: 'motopress_get_static_content',
                        nonce: parent.motopress.nonces.motopress_get_static_content,
                        staticFile: staticFile
                    },
                    dataType: 'html',
                    type: 'POST',
                    success: function(data) {
                        parent.MP.StaticEditor.myThis.modalStaticName
                            .val(staticName)
                            .attr('data-motopress-static-file', staticFile);

                        parent.MP.StaticEditor.myThis.modalStaticContent.val(data);

                        var editor = parent.tinyMCE.get('motopress-static-content')
                        if (typeof editor !== 'undefined') {
                            if (parent.MP.StaticEditor.myThis.editor == null) {
                                parent.MP.StaticEditor.myThis.editor = editor;
                            }
                            parent.MP.StaticEditor.myThis.editor.setContent(data);
                        }

                        parent.MP.Navbar.myThis.preload(false);
                        parent.MP.StaticEditor.myThis.modal.modal('show');
                    },
                    error: function() {
                        parent.MP.Flash.setFlash(localStorage.getItem('editStaticContentError'), 'error');
                        parent.MP.Navbar.myThis.preload(false);
                    }
                });
            });
        },

        makeTypeBlockTools: function(obj, selected) {
            if(MP.DragDrop.myThis.sidebarList != null) {
                var select = this.contentSelect.clone();
                var defaultOption = this.contentOption.clone();
                defaultOption.prop({
                    'value': 'default',
                    'selected': true,
                    'disabled': true,
                    'text': 'Select content'
                }).prependTo(select);
                $.each(MP.DragDrop.myThis.sidebarList, function(index, value) {
                    var option = MP.Tools.myThis.contentOption.clone();
                    option.prop({
                        value: index,
                        text: value.name
                    }).attr('data-type', value.type).appendTo(select);
                });

                obj.children('.motopress-helper').children('.motopress-tools')
                   .children('.motopress-tools-inner').append(select);

                if (selected) {
                    select.find('[value="'+selected+'"]').prop('selected', true);
                } else {
                    obj.attr('data-motopress-sidebar-id', defaultOption.val());
                }

                this.changeType(select);

                this.makeSelectpicker(select);
            }
        },

        changeStaticBlockType: function(select) {
            select.on('change', function() {
                parent.MP.Navbar.myThis.preload(true);
                var staticFile = $(this).find('option:selected').val();
                $.ajax({
                    url: parent.motopress.ajaxUrl,
                    data: {
                        action: 'motopress_get_static_block',
                        nonce: parent.motopress.nonces.motopress_get_static_block,
                        staticFile: staticFile
                    },
                    dataType: 'html',
                    type: 'POST',
                    success: function(data) {
                        var helper = select.closest('.motopress-helper');
                        var span = helper.parent('[class*="span"][data-motopress-type="static"]');
                        span.find('.motopress-block-content').html(data);
                        span.attr('data-motopress-static-file', staticFile);
                        MP.Resizer.myThis.updateSplitterHeight(span, 'edit');
                        parent.MP.Navbar.myThis.preload(false);
                    },
                    error: function() {
                        parent.MP.Flash.setFlash(localStorage.getItem('changeStaticBlockError'), 'error');
                        parent.MP.Flash.showMessage();
                        parent.MP.Navbar.myThis.preload(false);
                    }
                });
            });
        },

        changeLoopBlockType: function(select) {
            select.on('change', function() {
                var loopFile = $(this).find('option:selected').val();
                var loopBlock = select.closest('[class*="span"]');
                var loopId = loopBlock.attr('data-motopress-id');
                if (!loopId || typeof loopId == 'undefined') loopId = parent.MP.Utils.uniqid()

                loopBlock.attr('data-motopress-loop-file', loopFile);
                loopBlock.attr('data-motopress-id', loopId);

                var blockContent = loopBlock.children('.motopress-block-content');
                MP.DragDrop.myThis.getLoop(blockContent, loopId);
            });
        },

        changeType: function(select) {
            select.on('change', function() {
                parent.MP.Navbar.myThis.preload(true);
                var selected = $(this).find('option:selected');
                var val = selected.val();
                var type = selected.attr('data-type');
                $.ajax({
                    url: parent.motopress.ajaxUrl,
                    data: {
                        action: 'motopress_get_sidebar',
                        nonce: parent.motopress.nonces.motopress_get_sidebar,
                        id: val,
                        type: type
                    },
                    dataType: 'html',
                    type: 'POST',
                    success: function(data) {
                        var span = select.closest('[class*="span"]');
                        span.find('.motopress-block-content').html(data);
                        if (type == 'dynamic') {
                            span.attr({
                                'data-motopress-type': 'dynamic-sidebar',
                                'data-motopress-sidebar-id': val
                            }).removeAttr('data-motopress-sidebar-file');
                        } else if (type == 'static') {
                            span.attr({
                                'data-motopress-type': 'static-sidebar',
                                'data-motopress-sidebar-file': val
                            });
                            if (val == 'sidebar.php') {
                                span.removeAttr('data-motopress-sidebar-id');
                            } else {
                                var exp = new RegExp('^sidebar-(.+)\.php$', 'i');
                                var matches = exp.exec(val);
                                if (matches != null) {
                                    span.attr('data-motopress-sidebar-id', matches[1]);
                                }
                            }
                        }
                        MP.Resizer.myThis.updateSplitterHeight(span, 'edit');
                        parent.MP.Navbar.myThis.preload(false);
                    },
                    error: function() {
                        parent.MP.Flash.setFlash(localStorage.getItem('changeSidebarError'), 'error');
                        parent.MP.Flash.showMessage();
                        parent.MP.Navbar.myThis.preload(false);
                    }
                });
            });
        },

        makeLayoutWrapperTools: function(layoutWrapper) {
            //if (layoutWrapper.attr('data-motopress-wrapper-type') !== 'content') {
                this.makeContentLayoutWrapperBtn(layoutWrapper);
            //}
            //this.makeRemovableLayoutWrapper(layoutWrapper);
        },

        makeContentLayoutWrapperBtn: function(layoutWrapper) {
            var wrapperType = layoutWrapper.attr('data-motopress-wrapper-type');
            var list = null;
            switch(wrapperType) {
                case 'header':
                    list = MP.DragDrop.myThis.headerList;
                    break;
                case 'footer':
                    list = MP.DragDrop.myThis.footerList;
                    break;
            }

            if (list !== null && Object.keys(list).length > 1) {
                var layoutWrapperTools = layoutWrapper.children('.motopress-helper').children('.motopress-layout-wrapper-tools');
    //            var btn = layoutWrapperTools.children('.motopress-content-layout-wrapper-btn');
    //            var popover = btn.next('.popover');
    //            btn.on('click', function() {
    //                popover.toggle();
    //            });

                var select = this.contentSelect.clone();
                var defaultOption = this.contentOption.clone();
                defaultOption.prop({
                    'value': 'default',
                    'selected': true,
                    'disabled': true,
                    'text': localStorage.getItem('selectContent')
                }).prependTo(select);

                $.each(list, function(index, value) {
                    var option = MP.Tools.myThis.contentOption.clone();
                    option.prop({
                        value: 'wrapper/' + index,
                        text: value
                    }).appendTo(select);
                });

                var wrapperFile = layoutWrapper.attr('data-motopress-wrapper-file');
                if (wrapperFile) {
                    select.find('[value="' + wrapperFile + '"]').prop('selected', true);
                }

//                popover.find('.popover-title').text(select.prop('title'));
//                popover.find('.popover-content').append(select);
                layoutWrapperTools.append(select);

//                popover.css({
//                    //'top': -((layoutWrapperTools.outerHeight() / 2) - (btn.outerHeight() / 2)) + 'px',
//                    'top': -((popover.height() / 2) + parseFloat(popover.css('margin-top')) - (layoutWrapperTools.outerHeight() / 2)) + 'px',
//                    'left': layoutWrapperTools.outerWidth() + 'px'
//                });

                select.selectpicker({
                    size: 10
                });

                this.changeContentLayoutWrapper(select);
            }
        },

        disableUsedContentOption: function(oldValue, newValue, select) {
            var selects = $('.motopress-layout-wrapper-tools').find('select.motopress-content-select');
            var option = null;
            if(typeof oldValue == 'undefined' && typeof newValue == 'undefined' && typeof select == 'undefined') {
//                MP.Tools.myThis.usedWrapperList.length = 0;
                selects.each(function() {
                    var selected = $(this).find('option:selected');
                    MP.Tools.myThis.usedWrapperList[selected.val()] = selected.text();
                });
                selects.each(function() {
                    var $this = $(this);
                    var selected = $this.children('option:selected').val();
                    $.each(MP.Tools.myThis.usedWrapperList, function(index) {
                        if(index != selected) {
//                            $this.children('option[value="' + index + '"]').prop('disabled', true);
                            option = $this.children('option[value="' + index + '"]');
                            option.prop('disabled', true);
                            parent.MP.BootstrapSelect.setDisabled(option, true);
                        }
                    });
                });
            } else {
                delete this.usedWrapperList[oldValue];
                //parent.MP.Utils.removeByValue(oldValue, this.usedWrapperList);
                if (!$.isEmptyObject(newValue)) this.usedWrapperList[newValue.val] = newValue.text;
                selects.each(function() {
                    var $this = $(this);
                    if(oldValue !== 'default' && $this.is('select')) {
//                        $this.children('option[value="' + oldValue + '"]').prop('disabled', false);
                        option = $this.children('option[value="' + oldValue + '"]');
                        option.prop('disabled', false);
                        parent.MP.BootstrapSelect.setDisabled(option, false);
                    }
                    if($this[0] !== select[0]) {
//                        $(this).children('option[value="' + newValue + '"]').prop('disabled', true);
                        option = $(this).children('option[value="' + newValue.val + '"]');
                        option.prop('disabled', true);
                        parent.MP.BootstrapSelect.setDisabled(option, true);
                    }
                });
            }
        },

        changeContentLayoutWrapper: function(select) {
            /*
            var oldValue = null;
            select.next().find('button').on('click', function() {
                oldValue = select.find('option:selected').val();
            });
            */

            select.on('change', function() {
                var selected = $(this).find('option:selected');
                var newValue = {
                    val: selected.val(),
                    text: selected.text()
                };
                var selectedPage = parent.MP.Navbar.myThis.element.find('option:selected');

                $.ajax({
                    url: parent.motopress.ajaxUrl,
                    data: {
                        action: 'motopress_get_wrapper',
                        nonce: parent.motopress.nonces.motopress_get_wrapper,
                        wrapper: newValue.val
                    },
                    dataType: 'html',
                    type: 'POST',
                    success: function(content) {
                        var layoutWrapper = select.closest('[class*="span"][data-motopress-wrapper-type]');
                        var oldWrapperFile = layoutWrapper.attr('data-motopress-wrapper-file');
                        var obj = parent.MP.Iframe.contents.find('html').clone();
                        layoutWrapper.attr({
                            'data-motopress-wrapper-file': newValue.val,
                            'data-motopress-new': 1
                        });

                        var layoutWrapperClone = obj.find('[class*="span"][data-motopress-wrapper-file="'+ oldWrapperFile +'"][data-motopress-wrapper-type]');
                        layoutWrapperClone.attr({
                            'data-motopress-wrapper-file': newValue.val,
                            'data-motopress-new': 1
                        });
                        var helperClone = layoutWrapperClone.children('.motopress-helper');
                        helperClone.siblings().remove();
                        helperClone.after(content);

                        parent.MP.Navbar.myThis.clearHelpers(obj);
                        parent.MP.Navbar.myThis.hideHiddenBlocks(obj, false);
                        parent.MP.Navbar.myThis.clearParams(obj);

                        var data = obj.find('body div#motopress-main').html();

                        $.ajax({
                            url: parent.motopress.ajaxUrl,
                            data: {
                                action: 'motopress_get_wrapper',
                                nonce: parent.motopress.nonces.motopress_get_wrapper,
                                link: selectedPage.val(),
                                page: selectedPage.attr('data-template'),
                                newWrapper: newValue.val,
                                data: data
                            },
                            dataType: 'html',
                            type: 'POST',
                            success: function(data) {
                                var helper = select.closest('.motopress-helper');
                                helper.siblings().remove();
                                var content = $(data).find('[class*="span"][data-motopress-wrapper-file="'+ newValue.val +'"][data-motopress-wrapper-type]').children('.row');
                                helper.after(content);

                                MP.DragDrop.myThis.recursiveAddHandleMiddle(layoutWrapper);
                                MP.DragDrop.myThis.makeEditable(layoutWrapper.find('[class*="span"]:not([data-motopress-wrapper-type])'));

                                //MP.Tools.myThis.disableUsedContentOption(oldValue, newValue, select);
                            },
                            error: function(jqXHR) {
                                var error = JSON.parse(jqXHR.responseText);
                                if (error.debug) {
                                    console.log(error.message);
                                } else {
                                    parent.MP.Flash.setFlash(error.message, 'error');
                                    parent.MP.Flash.showMessage();
                                }
                            }
                        });
                    },
                    error: function() {
                        parent.MP.Flash.setFlash(localStorage.getItem('changeContentLayoutError'), 'error');
                        parent.MP.Flash.showMessage();
                    }
                });
                //select.closest('.popover').hide();
            });
        },

        makeRemovableLayoutWrapper: function(layoutWrapper) {
            var btn = layoutWrapper.children('.motopress-helper').children('.motopress-layout-wrapper-tools').children('.motopress-remove-layout-wrapper-btn');
            btn.on('click', function() {
                var spanRemovable = $(this).closest('[class*="span"][data-motopress-wrapper-type]');
                var spanClassRemovable = MP.DragDrop.getSpanClass(spanRemovable.prop('class').split(' '));
                var rowFrom = spanRemovable.parent('.row');

                spanClassRemovable = MP.DragDrop.myThis.removeEmptyBlocks(spanRemovable, spanClassRemovable);

                spanRemovable.children('.motopress-helper').children('.motopress-layout-wrapper-tools').children().tooltip('hide');

                var select = spanRemovable.children('.motopress-helper').children('.motopress-layout-wrapper-tools').find('.motopress-content-select');
                //var oldValue = select.children('option:selected').val();
                //MP.Tools.myThis.disableUsedContentOption(oldValue, null, select);

                spanRemovable.remove();

                rowFrom = MP.DragDrop.myThis.clearIfEmpty(rowFrom);

                MP.DragDrop.myThis.resize(rowFrom, spanClassRemovable, null, spanRemovable);

                MP.DragDrop.myThis.resizer.updateResizableOptions(null, rowFrom, null);

                if(spanRemovable.attr('data-motopress-type') == 'content') {
                    var newBlock = MP.LeftMenu.myThis.leftMenu.find('.motopress-new-block[data-type="content"]');
                    newBlock.draggable('enable');
                }
            });
        },

        makeSelectpicker: function(select) {
            select.selectpicker({size: 10});
            select.next().find('button').on('click', function() {
                $('.motopress-helper').children('.motopress-tools').children('.motopress-tools-inner').css('z-index', '1002');
                $(this).closest('.motopress-tools').children('.motopress-tools-inner').css('z-index', '1003');
            });
        }
    })
});