enum Club {
    None,
    BcClub = 2,
    BcCafe,
    BdClub,
    BhClub,
    BiClub
}

function getIdOfClub (club: string): Club {
    switch (club) {
        case "bc-Club":
            return Club.BcClub;
        case "bc-Café":
            return Club.BcCafe;
    }
    return Club.None;
}
