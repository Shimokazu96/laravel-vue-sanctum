import { ref } from "vue";

export default function () {
    //入力必須
    const isRequiredText = (value) => {
        let result = false;
        if (!value) {
            result = true;
        }
        return result;
    };
    //メールアドレス
    const isInValidEmail = (value) => {
        let result = false;
        const regex = new RegExp(
            /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/
        );
        result = !regex.test(value);
        return result;
    };

    return {
        isRequiredText,
        isInValidEmail
    };
}
