/**
     * Class for handling country code functionality in Elementor forms.
     */
class CCFEF extends elementorModules.frontend.handlers.Base {

    /**
     * Retrieves the default settings for the country code functionality.
     * @returns {Object} An object containing selector configurations.
     */
    getDefaultSettings() {
        return {
            selectors: {
                inputTelTextArea: 'textarea.ccfef_country_code_data_js',
                intlInputSpan: '.ccfef-editor-intl-input',
                submitButton: 'div.elementor-field-type-submit button'
            },
        };
    }

    /**
     * Retrieves the default elements based on the settings defined.
     * @returns {Object} An object containing jQuery elements for the text area and editor span.
     */
    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        return {
            $textArea: this.$element.find(selectors.inputTelTextArea),
            $intlSpanElement: this.$element.find(selectors.intlInputSpan),
            $submitButton: this.$element.find(selectors.submitButton),
        };
    }

    /**
     * Binds events to the elements. This method is intended to be overridden by subclasses to add specific event handlers.
     */
    bindEvents() {
        this.telId = new Array(); // Array to store telephone IDs

        this.includeCountries = {}; // Object to store countries to include

        this.excludeCountries = {}; // Object to store countries to exclude

        this.defaultCountry = {};

        this.commonCountries = {}; // NEW: Object to store flag if common-countries === 'same'

        this.iti = {}; // Object to store international telephone input instances

        this.getIntlUserData(); // Retrieves international telephone input data from the DOM and stores them for further processing.

        this.appendCountryCodeHandler(); // Appends a country code handler to each telephone input field to manage country code functionality.

        this.addCountryCodeInputHandler(); // Adds a country code input handler that initializes the international telephone input functionality.

        this.customFlags() // custom load svg flags

        this.removeInputTelSpanEle(); // Removes the telephone input span element from the DOM, typically used to clean up after modifications.

        this.intlInputValidation(); // Validates the international input fields to ensure they meet specific criteria.

    }

    /**
     * Method to handle appending country code.
     */
    appendCountryCodeHandler() {
        this.telId.forEach(data => {
            this.addCountryCodeIconHandler(data.formId, data.fieldId, data.customId);
        });
    }


    /**
     * Method to handle country code input.
     */
    addCountryCodeInputHandler() {
        const itiArr = this.iti;

        Object.keys(itiArr).forEach(key => {
            const iti = itiArr[key];
            const inputElement = iti.telInput;

            let previousCountryData = iti.getSelectedCountryData();
            let previousCode = `+${previousCountryData.dialCode}`;
            let keyUpEvent = false;

            const resetKeyUpEventStatus = () => {
                keyUpEvent = false;
            };

            const handleCountryChange = (e) => {
                this.customFlags();
                const currentCountryData = iti.getSelectedCountryData();
                const currentCode = `+${currentCountryData.dialCode}`;
                if (e.type === 'keydown' || e.type=== 'input') {
                    keyUpEvent = true;
                    clearTimeout(resetKeyUpEventStatus);
                    setTimeout(resetKeyUpEventStatus, 400);

                    if (previousCountryData.dialCode !== currentCountryData.dialCode) {
                        previousCountryData = currentCountryData;
                    } else if (previousCountryData.dialCode === currentCountryData.dialCode && previousCountryData.iso2 !== currentCountryData.iso2) {
                        iti.setCountry(previousCountryData.iso2);
                    }
                } else if (e.type === "countrychange") {
                    if (keyUpEvent) {
                        return;
                    }

                    previousCountryData = currentCountryData;
                }

                this.updateCountryCodeHandler(e.currentTarget, currentCode, previousCode);
                previousCode = currentCode;
            };

            // Attach event listeners for both keyup and country change events
            inputElement.addEventListener('keydown', handleCountryChange);
            inputElement.addEventListener('input', handleCountryChange);
            inputElement.addEventListener('countrychange', handleCountryChange);
        });
    }

     /**
     * Method to handle adding country code icon.
     * @param {string} id - The ID of the element.
     * @param {string} widgetId - The widget ID.
     */
     addCountryCodeIconHandler(formId, widgetId, inputId) {
        const utilsPath = CCFEFCustomData.pluginDir + 'assets/intl-tel-input/js/utils.min.js';
        const telFIeld = jQuery(`.elementor-widget.elementor-widget-form[data-id="${formId}"] .elementor-field-type-tel.elementor-field-group input[type="tel"]#${inputId}`)[0];
        
        if (undefined !== telFIeld) {
            let includeCountries = [];
            let excludeCountries = [];
            let defaultCountry = 'in';
            const defaultCoutiresArr = ['in','us','gb','ru','fr','de','br','cn','jp','it'];
            const uniqueId = `${formId}${widgetId}`;
    
            if (this.includeCountries.hasOwnProperty(uniqueId) && this.includeCountries[uniqueId].length > 0) {
                defaultCountry = this.includeCountries[uniqueId][0];
                includeCountries = [...this.includeCountries[uniqueId]];
            }
    
            if (this.excludeCountries.hasOwnProperty(uniqueId) && this.excludeCountries[uniqueId].length > 0) {
                let uniqueValue = defaultCoutiresArr.filter((value) => !this.excludeCountries[uniqueId].includes(value));
                defaultCountry = uniqueValue[0];
                excludeCountries = [...this.excludeCountries[uniqueId]];
            }
    
            if (this.defaultCountry[uniqueId] && '' !== this.defaultCountry[uniqueId]) {
                defaultCountry = this.defaultCountry[uniqueId];
            }
    
            // Initialize the international telephone input.
            const iti = window.intlTelInput(telFIeld, {
                initialCountry: defaultCountry,
                utilsScript: utilsPath,
                formatOnDisplay: false,
                formatAsYouType: true,
                autoFormat: false,
                containerClass: 'cfefp-intl-container',
                useFullscreenPopup: false,
                onlyCountries: includeCountries,
                excludeCountries: excludeCountries,
                customPlaceholder: (selectedCountryPlaceholder, selectedCountryData) => {
                    
                    // If the commonAttr flag is 'same', return a simple placeholder.
                    if (this.commonCountries[uniqueId]) {
                        return "No country found";
                    }
                    
                    if (!selectedCountryData || !selectedCountryPlaceholder || !selectedCountryData.dialCode) {
                        return "No country found";
                    }
                    
                    let placeHolder = selectedCountryPlaceholder;
                    if ('in' === selectedCountryData.iso2) {
                        placeHolder = selectedCountryPlaceholder.replace(/^0+/, '');
                    }
                    
                    const placeholderText = `+${selectedCountryData.dialCode} ${placeHolder}`;
                    return placeholderText.replace(/\s/g, '');
                },            
            });
            
            // Retrieve commonAttr from the hidden span to decide whether to hide the country list.
            const intlSpan = document.querySelector(`.elementor-widget.elementor-widget-form[data-id="${formId}"] .ccfef-editor-intl-input[data-field-id="${widgetId}"]`);
            const commonAttr = intlSpan ? intlSpan.getAttribute('data-common-countries') : '';
            if ('same' === commonAttr && this.commonCountries[uniqueId] && '' !== includeCountries && '' !== excludeCountries) {
                const countryList = iti.countryList;
                if (countryList && countryList.classList.contains('iti__country-list')) {
                    countryList.style.display = 'none';
                }
            }
             else {
                // Filter the country list: show only the countries that are in includeCountries and not in excludeCountries.
                const countryList = iti.countryList;
if (countryList && countryList.classList.contains('iti__country-list')) {
    // Select all individual country items.
    const countryItems = countryList.querySelectorAll('.iti__country');
    
    // Hide items if they are in the excludeCountries list.
    countryItems.forEach(function(item) {
        const countryCode = item.getAttribute('data-country-code');
        if (excludeCountries.includes(countryCode)) {
            item.style.display = 'none';
        }
    });
    
    // Get the remaining visible country items.
    const visibleCountries = Array.from(countryItems).filter(item => item.style.display !== 'none');
    
    // Filter those visible items that are present in includeCountries.
    const includedVisibleCountries = visibleCountries.filter(item => {
        const countryCode = item.getAttribute('data-country-code');
        return includeCountries.includes(countryCode);
    });
    
    // If there are any visible items in the include list, select the first one.
    if (includedVisibleCountries.length > 0) {
        const selectedItem = includedVisibleCountries.find(item => item.getAttribute('aria-selected') === 'true');
        if (!selectedItem) {
            const firstItem = includedVisibleCountries[0];
            firstItem.setAttribute('aria-selected', 'true');
            // Update the intlTelInput instance so that the country selection is reflected in the field.
            const newCountryCode = firstItem.getAttribute('data-country-code');
            iti.setCountry(newCountryCode);
        }
    }
}

            }

            telFIeld.removeAttribute('pattern');
            this.iti[formId + widgetId] = iti;
        }
    }
    
    
    /**
     * Method to update country code.
     * @param {Element} element - The input element.
     * @param {string} countryCode - The country code.
     * @param {string} previousCode - The previous country code.
     */
    updateCountryCodeHandler(element, currentCode, previousCode) {
        let value = element.value;
        
        if(currentCode && '+undefined' === currentCode || ['','+'].includes(value)){
            return;
        }
        
        if (currentCode !== previousCode) {
            value = value.replace(new RegExp(`^\\${previousCode}`), '');
        }
        
        if (!value.startsWith(currentCode)) {
            value = value.replace(/\+/g, '');
            element.value = currentCode + value;
        }
    }

    customFlags() {
        const selectedCountries = this.$element.find('.cfefp-intl-container .iti__country-container .iti__flag:not(.iti__globe)');
    
        // Loop through each flag element
        selectedCountries.each(function() {
            const selectedCountry = this;  // 'this' refers to the current element in the loop
            const classList = selectedCountry.className.split(' '); 
            
            if (classList[1]) {
                const selectedCountryFlag = classList[1].split('__')[1]; 
                const svgFlagPath = CCFEFCustomData.pluginDir + `assets/flags/${selectedCountryFlag}.svg`;

                // Apply the styles dynamically to the current flag
                selectedCountry.style.backgroundImage = `url('${svgFlagPath}')`;
            } 
        });
    }
        
    /**
     * Removes the span element with class 'ccfef-editor-intl-input' from the DOM.
     */
    removeInputTelSpanEle() {
        this.$element.find('span.ccfef-editor-intl-input').remove();
    }

    /**
     * Retrieves and stores unique telephone input IDs from the Elementor editor span elements.
     */
    getIntlUserData() {
        const intelInputElement = this.elements.$intlSpanElement;
        const previousIds = [];
        intelInputElement.each((_, ele) => {
            const element = jQuery(ele);
            const includeCountries=element.data('include-countries');
            const excludeCountries=element.data('exclude-countries');
            const defaultCountry=element.data('defaultCountry');
            const commonAttr=element.data('common-countries');
            const inputId = element.data('id');
            const fieldId = element.data('field-id');
            const formId = element.closest('.elementor-element.elementor-widget-form').data('id');
            const currentId = `${formId}${fieldId}`;

            if ('same' === commonAttr && '' === includeCountries && '' !== excludeCountries) {
                // NEW: Store flag for use in the custom placeholder function
                this.commonCountries[currentId] = true;
            } else {
                if ('' !== includeCountries) {
                    if(isNaN(includeCountries)){
                        const splitIncludeCountries = includeCountries.split(',');
                        this.includeCountries[currentId] = splitIncludeCountries;
                    }
                }
                
                if ('' !== excludeCountries) {
                    if(isNaN(excludeCountries)){
                        const splitExcludeCountries = excludeCountries.split(',');
                        this.excludeCountries[currentId] = splitExcludeCountries;
                    }
                }
            
                // NEW: If commonAttr is not 'same' but all values in includeCountries are also present in excludeCountries, set commonCountries flag
                if ('same' !== commonAttr && '' !== includeCountries && '' !== excludeCountries) {
                    if(isNaN(includeCountries)){
                        const includeArray = includeCountries.split(',').map(item => item.trim());
                
                   if(isNaN(excludeCountries)){
                    const excludeArray = excludeCountries.split(',').map(item => item.trim());
                    const allIncludedPresent = includeArray.every(country => excludeArray.includes(country));
                    if (allIncludedPresent) {
                            this.commonCountries[currentId] = true;
                        }
                    }
                }
                }
                
                if ('' !== defaultCountry) {
                     this.defaultCountry[currentId] = defaultCountry;
                }
            }
            

            if (!previousIds.includes(currentId)) {
                this.telId.push({ formId, fieldId, customId: inputId });
                previousIds.push(currentId);
            }
        });
    }



    /**
     * Validates the international telephone input fields when the submit button is clicked.
     * It checks if the number is valid and displays appropriate error messages next to the input field.
     */
    intlInputValidation() {
        this.elements.$submitButton.on('click', (e) => {
            const itiArr = this.iti;

            if (Object.keys(itiArr).length > 0) {
                Object.keys(itiArr).forEach(data => {
                    const iti = itiArr[data];
              
                    const inputTelElement = iti.telInput;

                    if('' !== inputTelElement.value){
                        inputTelElement.value=inputTelElement.value.replace(/[^0-9+]/g, '');
                    }

                    const parentWrp = inputTelElement.closest('.elementor-field-group');
                    const telContainer=parentWrp.querySelector('.cfefp-intl-container');

                    if (telContainer && inputTelElement.offsetHeight) {
                        telContainer.style.setProperty('--cfefp-intl-tel-button-height', `${inputTelElement.offsetHeight}px`);
                    }

                    const errorContainer = jQuery(inputTelElement).parent();
                    errorContainer.find('span.elementor-message').remove();

                    const errorMap = CCFEFCustomData.errorMap;
                    let errorMsgHtml = '<span class="elementor-message elementor-message-danger elementor-help-inline elementor-form-help-inline" role="alert">';
                    if('' === inputTelElement.value){
                        return;
                    };
                    if (iti.isValidNumber()) {
                        jQuery(inputTelElement).closest('.cfefp-intl-container').removeClass('elementor-error');
                    } else {
                        const errorType = iti.getValidationError();
                        if (errorType !== undefined && errorMap[errorType]) {
                            errorMsgHtml += errorMap[errorType] + '</span>';
                            jQuery(inputTelElement).closest('.cfefp-intl-container').addClass('elementor-error');
                            jQuery(inputTelElement).after(errorMsgHtml);
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    }
    
}


jQuery(window).on('elementor/frontend/init', () => {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(CCFEF, {
            $element,
        });
    };

    elementorFrontend.hooks.addAction('frontend/element_ready/form.default', addHandler);
});

