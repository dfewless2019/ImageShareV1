(function($) {
        $.fn.extend({
                selectRange: function(selectionStart, selectionEnd) {
                        // if there is no content in the input field, then we return false
                        if ($(this).length === 0)
                                return false;
                                
                        // retrieve the input object without jQuery funcitonality
                        var input = $(this).get(0);
                                
                        // set defaults
                        if (typeof selectionStart === 'undefined')
                                var selectionStart = 0;
                        if (typeof selectionEnd === 'undefined')
                                var selectionEnd = selectionStart;
                        
                        // do the selection and/or cursor placement
                        if (input.setSelectionRange) {
                                input.focus();
                                input.setSelectionRange(selectionStart, selectionEnd);
                        } else if (input.createTextRange) {
                                var range = input.createTextRange();
                                range.collapse(true);
                                range.moveEnd('character', selectionEnd);
                                range.moveStart('character', selectionStart);
                                range.select();
                        }
                },
                
                caretTo: function(index) {
                        $(this).selectRange(parseInt(index));
                },
                
                caretToStart: function() {
                        $(this).selectRange(0);
                },
                
                caretToEnd: function() {
                        $(this).selectRange($(this).length+1);
                },
                
                strSelect: function(string) {
                        var input = $(this);
                        var match = new RegExp(string, "i").exec(input.value);
                        if (match)
                                $(this).selectRange(match.index, match.index + match[0].length);
                },
                
                replaceSelection: function(replaceString) {
                        var input = $(this);
                        if (input.setSelectionRange) {
                                var selectionStart = input.selectionStart;
                                var selectionEnd = input.selectionEnd;
                                input.value = input.value.substring(0, selectionStart)
                                        + replaceString
                                        + input.value.substring(selectionEnd);
                                if (selectionStart != selectionEnd) // has there been a selection
                                        $(this).selectRange(selectionStart, selectionStart + replaceString.length);
                                else
                                        $(this).caretTo(selectionStart + replaceString.length);
                        } else if (document.selection) {
                                var range = document.selection.createRange();
                                if (range.parentElement() == input) {
                                        var isCollapsed = range.text == '';
                                        range.text = replaceString;
                                        if (!isCollapsed)  {
                                                range.moveStart('character', -replaceString.length);
                                                range.select();
                                        }
                                }
                        }
                }
        });
})(jQuery);
