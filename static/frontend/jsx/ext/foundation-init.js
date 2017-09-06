import { Foundation } from 'bower_components/foundation-sites/js/foundation.core';
import { rtl, GetYoDigits, transitionend } from 'bower_components/foundation-sites/js/foundation.util.core';

import { Box } from 'bower_components/foundation-sites/js/foundation.util.box'
import { onImagesLoaded } from 'bower_components/foundation-sites/js/foundation.util.imageLoader';
import { Keyboard } from 'bower_components/foundation-sites/js/foundation.util.keyboard';
import { MediaQuery } from 'bower_components/foundation-sites/js/foundation.util.mediaQuery';
// import { Motion, Move } from 'bower_components/foundation-sites/js/foundation.util.motion';
// import { Nest } from 'bower_components/foundation-sites/js/foundation.util.nest';
// import { Timer } from 'bower_components/foundation-sites/js/foundation.util.timer';
// import { Touch } from 'bower_components/foundation-sites/js/foundation.util.touch';
import { Triggers } from 'bower_components/foundation-sites/js/foundation.util.triggers';

// import { Abide } from 'bower_components/foundation-sites/js/foundation.abide';
import { Accordion } from 'bower_components/foundation-sites/js/foundation.accordion';
// import { AccordionMenu } from 'bower_components/foundation-sites/js/foundation.accordionMenu';
// import { Drilldown } from 'bower_components/foundation-sites/js/foundation.drilldown';
// import { Dropdown } from 'bower_components/foundation-sites/js/foundation.dropdown';
// import { DropdownMenu } from 'bower_components/foundation-sites/js/foundation.dropdownMenu';
// import { Equalizer } from 'bower_components/foundation-sites/js/foundation.equalizer';
// import { Interchange } from 'bower_components/foundation-sites/js/foundation.interchange';
// import { Magellan } from 'bower_components/foundation-sites/js/foundation.magellan';
import { OffCanvas } from 'bower_components/foundation-sites/js/foundation.offcanvas';
// import { Orbit } from 'bower_components/foundation-sites/js/foundation.orbit';
// import { ResponsiveMenu } from 'bower_components/foundation-sites/js/foundation.responsiveMenu';
// import { ResponsiveToggle } from 'bower_components/foundation-sites/js/foundation.responsiveToggle';
// import { Reveal } from 'bower_components/foundation-sites/js/foundation.reveal';
// import { Slider } from 'bower_components/foundation-sites/js/foundation.slider';
// import { SmoothScroll } from 'bower_components/foundation-sites/js/foundation.smoothScroll';
import { Sticky } from 'bower_components/foundation-sites/js/foundation.sticky';
import { Tabs } from 'bower_components/foundation-sites/js/foundation.tabs';
// import { Toggler } from 'bower_components/foundation-sites/js/foundation.toggler';
// import { Tooltip } from 'bower_components/foundation-sites/js/foundation.tooltip';
// import { ResponsiveAccordionTabs } from 'bower_components/foundation-sites/js/foundation.responsiveAccordionTabs';

(()=>{
    Foundation.addToJquery($);

    // Add Foundation Utils to Foundation global namespace for backwards
    // compatibility.

    Foundation.rtl = rtl;
    Foundation.GetYoDigits = GetYoDigits;
    Foundation.transitionend = transitionend;

    Foundation.Box = Box;
    Foundation.onImagesLoaded = onImagesLoaded;
    Foundation.Keyboard = Keyboard;
    Foundation.MediaQuery = MediaQuery;
    // Foundation.Motion = Motion;
    // Foundation.Move = Move;
    // Foundation.Nest = Nest;
    // Foundation.Timer = Timer;

    // Touch and Triggers previously were almost purely sede effect driven,
    // so no // need to add it to Foundation, just init them.

    // Touch.init($);
    Triggers.init($, Foundation);

    // Foundation.plugin(Abide, 'Abide');
    Foundation.plugin(Accordion, 'Accordion');
    // Foundation.plugin(AccordionMenu, 'AccordionMenu');
    // Foundation.plugin(Drilldown, 'Drilldown');
    // Foundation.plugin(Dropdown, 'Dropdown');
    // Foundation.plugin(DropdownMenu, 'DropdownMenu');
    // Foundation.plugin(Equalizer, 'Equalizer');
    // Foundation.plugin(Interchange, 'Interchange');
    // Foundation.plugin(Magellan, 'Magellan');
    Foundation.plugin(OffCanvas, 'OffCanvas');
    // Foundation.plugin(Orbit, 'Orbit');
    // Foundation.plugin(ResponsiveMenu, 'ResponsiveMenu');
    // Foundation.plugin(ResponsiveToggle, 'ResponsiveToggle');
    // Foundation.plugin(Reveal, 'Reveal');
    // Foundation.plugin(Slider, 'Slider');
    // Foundation.plugin(SmoothScroll, 'SmoothScroll');
    Foundation.plugin(Sticky, 'Sticky');
    Foundation.plugin(Tabs, 'Tabs');
    // Foundation.plugin(Toggler, 'Toggler');
    // Foundation.plugin(Tooltip, 'Tooltip');
    // Foundation.plugin(ResponsiveAccordionTabs, 'ResponsiveAccordionTabs');

    window.Foundation = Foundation;
})($);