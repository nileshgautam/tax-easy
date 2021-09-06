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

$(function () {
    $('.nav-link').click(function () {
        let selectedLink = $(this).attr('href');
        saveLsData('sl', selectedLink);
    });
    selectedURL();
});

