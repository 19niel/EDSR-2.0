document.addEventListener("DOMContentLoaded", function () {
    const branchSelect = document.getElementById("branch1");
    const regionSelect = document.getElementById("region1");

    // Company business rules lookup table
    const branchToRegionMap = {
        'MM': 'MM',
        'ANG': 'LUZON', 
        'CAB': 'LUZON', 
        'LAU': 'LUZON', 
        'BAT': 'LUZON', 
        'NAG': 'LUZON', 
        'SUB': 'LUZON',
        'BAC': 'VISAYAS', 
        'CEB': 'VISAYAS', 
        'DUM': 'VISAYAS', 
        'ILO': 'VISAYAS', 
        'TAC': 'VISAYAS',
        'CDO': 'MINDANAO', 
        'DAV': 'MINDANAO', 
        'GEN': 'MINDANAO', 
        'ZAM': 'MINDANAO'
    };

    if (branchSelect && regionSelect) {
        branchSelect.addEventListener("change", function () {
            const selectedBranch = this.value;
            if (branchToRegionMap[selectedBranch]) {
                regionSelect.value = branchToRegionMap[selectedBranch];
            }
        });
    }
});