
class SponsorCreateDto {
    name = null;
    uuid = null

    constructor(sponsor) {
        this.name = sponsor.name;
        this.uuid = sponsor.uuid;
    }
}

export default SponsorCreateDto;
