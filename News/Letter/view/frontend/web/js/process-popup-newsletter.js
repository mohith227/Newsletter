define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/mage',
    'jquery/ui'
], function ($, modal) {
    'use strict';
    
    $.widget('news.processPopupNewsletter', {

        /**
         *
         * @private
         */
        _create: function () {
            var self = this,
                popup_newsletter_options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: this.options.popupTitle,
                    buttons: false,
                    modalClass : 'popup-newsletter'
                };

            modal(popup_newsletter_options, this.element);

            setTimeout(function() {
                self._setStyleCss();
                var modal = self.element.modal('openModal');
            }, 3000);
            
            $(this.element).closest(".popup-newsletter").find("header button.action-close").on('click', function(e) {
                $.ajax({
                    url: 'http://127.0.0.1/book/newsletter/subscriber/new',
                    cache: true,
                    type: 'POST',
                    showLoader: true
                })
            })
            
            this.element.find('form').submit(function() {
                if ($(this).validation('isValid')) {
                    $.ajax({
                        url: $(this).attr('action'),
                        cache: true,
                        data: $(this).serialize(),
                        dataType: 'json',
                        type: 'POST',
                        showLoader: true
                    }).done(function (data) {
                        self.element.find('.messages .message div').html(data.message);
                        if (data.error) {
                            self.element.find('.messages .message').addClass('message-error error');
                        } else {
                            self.element.find('.messages .message').addClass('message-success success');
                            setTimeout(function() {
                                self.element.modal('closeModal');
                            }, 1000);
                        }
                        self.element.find('.messages').show();
                        setTimeout(function() {
                            self.element.find('.messages').hide();
                        }, 5000);
                    });
                }
                return false;
            });

            this._resetStyleCss();
        },

      

        /**
         * Set width of the popup
         * @private
         */
        _setStyleCss: function(width) {
            width = width || 400;
            if (window.innerWidth > 786) {
                this.element.parent().parent('.modal-inner-wrap').css({'max-width': width+'px'});
            }
        },
        

        /**
         * Reset width of the popup
         * @private
         */
        _resetStyleCss: function() {
            var self = this;
            $( window ).resize(function() {
                if (window.innerWidth <= 786) {
                    self.element.parent().parent('.modal-inner-wrap').css({'max-width': 'initial'});
                } else {
                    self._setStyleCss(self.options.innerWidth);
                }
            });
        }
    });

    return $.news.processPopupNewsletter;
});
