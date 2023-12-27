export function fetchApi(_url, params) {

    return fetch(_url, {
        method: 'POST',
        body: params,  // テキストとして送信
    })
    .then(async response => {
        // レスポンスをテキストとして取得
        const responseText = await response.text();
        
        // レスポンスをテキストとして取得
        const regex = /<div>([\s\S]*?)<\/div>/;
        const match = responseText.match(regex);

        let jsonResponseText;

        if (match) {
            jsonResponseText = match[1];

            // 末尾に " at line ..." がある場合のみそれを削除
            const atLineRegex = / at line \d+$/;
            if (atLineRegex.test(jsonResponseText)) {
                jsonResponseText = jsonResponseText.replace(atLineRegex, '');
            }
        } else {
            // <div> タグが見つからない場合は、レスポンステキスト全体を使用
            jsonResponseText = responseText;
        }

        let jsonResponse;
        try {
            jsonResponse = JSON.parse(jsonResponseText);
        } catch (e) {
            throw new Error("Failed to parse JSON from the response");
        }

        if (jsonResponse.status > 200) {
            throw new Error(`Server responded with status: ${jsonResponse.status}`);
        }

        return jsonResponse;
    })
    .catch(error => {
        // ここでエラーをハンドルするか、さらに上位の呼び出し元にエラーを伝播させることができる
        console.error("An error occurred:", error);
        throw error; // エラーを再スローして、呼び出し元でハンドルできるようにする
    });
}
