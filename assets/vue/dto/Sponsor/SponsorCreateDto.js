
class SponsorCreateDto {
    name = null;
    color = null;
    imageFile = null;

    constructor(sponsor) {
        this.name = sponsor.name;
        this.color = sponsor.color;
        this.imageFile = sponsor.imageFile;
    }
}

export default SponsorCreateDto;
