
class SponsorCreateDto {
    name = null;
    imageFile = null;

    constructor(sponsor) {
        this.name = sponsor.name;
        this.imageFile = sponsor.imageFile;
    }
}

export default SponsorCreateDto;
