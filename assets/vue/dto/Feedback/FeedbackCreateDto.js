class FeedbackCreateDto{
    name = null;
    email = null;
    message = null;
    recaptchaToken = null;

    constructor(feedback) {
        this.name = feedback.name;
        this.email = feedback.email;
        this.message = feedback.message;
        this.recaptchaToken = feedback.recaptchaToken;
    }
}

export default FeedbackCreateDto
