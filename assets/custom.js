// Local storage function
// Function to pick local storage key
function retriveLsData(FILE_KEY) {
    return localStorage.getItem(FILE_KEY);
};
// Function to save 
function saveLsData(FILE_KEY, data) {
    localStorage.setItem(FILE_KEY, JSON.stringify(data));
};
// Function to check local storage key
function hasLsData(FILE_KEY) {
    return localStorage.hasOwnProperty(FILE_KEY) ? true : false;
    // localStorage 
};
// Function to remove local storage key
function removeLsData(FILE_KEY) {
    localStorage.removeItem(FILE_KEY);
    // localStorage 
};

// Function to selected links
const selectedURL = () => {
    if (hasLsData('sl')) {
        let sl = JSON.parse(retriveLsData('sl'));
        let el = $('.nav-link');
        for (let i = 0; i < el.length; i++) {
            if (sl == el[i].href) {
                el[i].classList.add("active");
            }
        }
    }
};
// Function will be return current financial year
const getCurrentFiscalYear = () => {
    //get current date
    var today = new Date();
    //get current month
    var curMonth = today.getMonth();
    var fiscalYr = "";
    if (curMonth > 3) { //
        var nextYr1 = (today.getFullYear() + 1).toString();
        fiscalYr = today.getFullYear().toString() + "-" +nextYr1.charAt(0)+ +nextYr1.charAt(1)+nextYr1.charAt(2) + nextYr1.charAt(3);
    } else {
        var nextYr2 = today.getFullYear().toString();
        fiscalYr = (today.getFullYear() - 1).toString() + "-" +nextYr1.charAt(0)+ +nextYr1.charAt(1)+ + nextYr2.charAt(2) + nextYr2.charAt(3);
    }
    return fiscalYr;
};
// Function to get selected gst month array
const get_month = (fy) => {
    let finY = fy.split('-');
    let cy = finY[0];
    let jfm = finY[0].charAt(0) + finY[0].charAt(1) + finY[1];
    let fyarr = [`apr-${cy}`, `may-${cy}`, `jun-${cy}`, `july-${cy}`, `aug-${cy}`, `sept-${cy}`, `oct-${cy}`, `nov-${cy}`, `dec-${cy}`, `jan-${jfm}`, `feb-${jfm}`, `march-${jfm}`];

    return fyarr;
}

function capitalize(input) {
    var CapitalizeWords = input[0].toUpperCase();
    for (var i = 1; i <= input.length - 1; i++) {
        let currentCharacter,
            previousCharacter = input[i - 1];
        if (previousCharacter && previousCharacter == ' ') {
            currentCharacter = input[i].toUpperCase();
        } else {
            currentCharacter = input[i];
        }
        CapitalizeWords = CapitalizeWords + currentCharacter;
    }
    return CapitalizeWords;
}

$(function () {
    $('.nav-link').click(function () {
        let selectedLink = $(this).attr('href');
        saveLsData('sl', selectedLink);
    });
    selectedURL();
});

