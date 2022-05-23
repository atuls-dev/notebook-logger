<style type="text/css">
            .nl-esi-shadow .sec-title {
                border: 1px solid #ebebeb;
                background: #fff;
                color: #d54e21;
                padding: 2px 4px;
            }
            .nl-esi-shadow{
                border:1px solid #ebebeb; padding:5px 20px; background:#fff; margin-bottom:40px;
                -webkit-box-shadow: 4px 4px 10px 0px rgba(50, 50, 50, 0.1);
                -moz-box-shadow:    4px 4px 10px 0px rgba(50, 50, 50, 0.1);
                box-shadow:         4px 4px 10px 0px rgba(50, 50, 50, 0.1);
            }
</style>
<div class="wrap">
            <h1>Notebook Logger API Endpoints</h1>
    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Authentication</h4></legend>
        <p>You need to create a bearer token or you can use <a href="https://wordpress.org/plugins/jwt-auth/">jwt-auth</a> </p>
        <p><strong>Endpoint:</strong><code> /wp-json/jwt-auth/v1/token </code><p>
        <p><strong>Method:</strong><code> POST </code><p>
        <p><strong>Param:</strong><code> {"username": "your-username","password":"your-password"} </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
        "token": "******************************************************",
        "user_email": "***@***.com",
        "user_nicename": "****",
        "user_display_name": "****"
}</code></pre>
    </fieldset>

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Get Logs</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/ </code><p>
        <p><strong>Method:</strong><code> GET </code><p>
        <p><strong>Param:</strong><code> None    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
         "2021-09-27": [
        {
            "id": "1",
            "triggers": "11",
            "emotion": "12",
            "reason": "Just a test",
            "intensity": "medium",
            "etype"     : "craving Or smoking",
            "cope"  : '15',
            "time": "2021-09-27 04:54:00",
            "time_iso": "2021-09-27T04:54:00",
            "created_date": "2021-09-27",
            "updated_on": "2021-09-27 11:24:31",
            "created_on": "2021-09-27 11:24:31",
            "trigger_value": "hold",
            "emotion_value": "smoke",
            "cope_value": "abc",
        }
    ]
}</code></pre>
    </fieldset>

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Get Limited Logs</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/limited/{offset}/{limit}


 </code><p>
        <p><strong>Method:</strong><code> GET </code><p>
        <p><strong>Param:</strong><code> None    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
        [
    {
        "id": "32",
        "user_id": "1",
        "etype": "smoking",
        "triggers": "11",
        "emotion": "13",
        "cope": "16",
        "reason": "h",
        "intensity": "weak",
        "time": "2021-09-27 11:01:00",
        "time_iso": "2021-09-27T11:01:00",
        "created_date": "2021-09-27",
        "updated_on": "2021-09-27 16:35:43",
        "created_on": "2021-09-27 16:35:43",
        "trigger_value": "hold",
        "emotion_value": "Emotional",
        "cope_value": "abc",
    }
]
}</code></pre>
    </fieldset>

     <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Single Log</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/{POSTID} </code><p>
        <p><strong>Method:</strong><code> GET </code><p>
        <p><strong>Param:</strong><code> None    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
        "id": "32",
        "user_id": "1",
        "etype": "smoking",
        "triggers": "11",
        "emotion": "13",
        "cope": "16",
        "reason": "h",
        "intensity": "3",
        "time": "2021-09-27 05:21:58",
        "time_iso": "2021-09-27T05:21:58",
        "created_date": "2021-09-27",
        "updated_on": "2021-09-27 10:52:17",
        "created_on": "2021-09-27 10:52:17",
        "trigger_value": "hold",
        "emotion_value": "Emotional",
        "cope_value": "abc",
}</code></pre>
    </fieldset>

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Post Log</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/ </code><p>
        <p><strong>Method:</strong><code> POST </code><p>
        <p><strong>Param:</strong><pre><code>{
    "trigger": {
        "id":"1", //id: pass if option already exists
    },
    "emotion": {
        "tempID": "5bdhc7", //tempID: pass if new option is created
        "value": "angry"
    },
    "etype": "craving or smoking",
    "cope": {
        "tempID": "849asw", 
        "value": "walking"
    },
    "reason": "this is a test ",
    "intensity": 5,
    "time_iso": "2021-09-23T15:13:50.284Z"
}   </code></pre><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
        "id": "1",
        "triggers": "11",
        "emotion": "12",
        "reason": "Just a test",
        "intensity": "5",
        "etype"     : "craving Or smoking",
        "cope"  : '18',
        "time": "2021-09-27 05:21:58",
        "time_iso": "2021-09-27T05:21:58",
        "created_date": "2021-09-27",
        "updated_on": "2021-09-27 10:52:17",
        "created_on": "2021-09-27 10:52:17",
}</code></pre>
    </fieldset>

   

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Edit Logs</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/{POSTID} </code><p>
        <p><strong>Method:</strong><code> POST , PUT </code><p>
        <p><strong>Param:</strong><pre><code> {
    "trigger": {
        "id":"1", //id: pass if option already exists
    },
    "emotion": {
        "tempID": "5bdhc7", //tempID: pass if new option is created
        "value": "angry"
    },
    "etype": "craving or smoking",
    "cope": {
        "tempID": "849asw", 
        "value": "walking"
    },
    "reason": "updating test ",
    "intensity": 5,
    "time_iso": "2021-09-23T15:13:50.284Z"
} </code></pre><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
    "message": "Successfully Updated Logger",
    "log": {
        "user_id": "1",
        "triggers": "11",
        "emotion": "15",
        "etype": "smoking",
        "cope": "",
        "reason": "updating log api",
        "intensity": "2",
        "time": "2021-09-27 05:21:58",
        "created_date": "2021-09-27"
    }
}</code></pre>
    </fieldset>

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Delete Logs</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/{POSTID} </code><p>
        <p><strong>Method:</strong><code> DELETE </code><p>
        <p><strong>Param:</strong><code> None    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{"message":"logger deleted successfully"}</code></pre>
    </fieldset>

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Post Option</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/option </code><p>
        <p><strong>Method:</strong><code> POST </code><p>
        <p><strong>Param:</strong><code> {"type": "trigger","value":"hold"}    </code><p>
        <p><strong>Parameter Details:</strong><code> "type": supported values( 'trigger','emotion', 'cope' )    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
    "message": "Successfully Added Option",
    "data": {
        "user_id": "1",
        "type": "trigger",
        "value": "hold",
        "id": 11
    }
}</code></pre>
    </fieldset>

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Get Options</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/options </code><p>
        <p><strong>Method:</strong><code> GET </code><p>
        <p><strong>Param:</strong><code> None    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
    "triggers": {
        "your-triggers": [
            {
                "id": "1",
                "user_id": "1",
                "type": "trigger",
                "value": "Smoking"
            }
        ],
        "other-triggers": [
            {
                "id": "3",
                "user_id": null,
                "type": "trigger",
                "value": "Hold"
            },
            {
                "id": "4",
                "user_id": null,
                "type": "trigger",
                "value": "Drinking"
            }
        ]
    },
    "emotions": {
        "your-emotions": [
            {
                "id": "2",
                "user_id": "1",
                "type": "emotion",
                "value": "emotion custom"
            }
        ],
        "other-emotions": [
            {
                "id": "5",
                "user_id": null,
                "type": "emotion",
                "value": "emotional"
            }
        ]
    },
    "copes": {
        "your-copes": [],
        "other-copes": [
            {
                "id": "7",
                "user_id": null,
                "type": "cope",
                "value": "abc"
            }
        ]
    }
}</code></pre>
    </fieldset>

  <!--   <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Get formdata</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/formdata </code><p>
        <p><strong>Method:</strong><code> GET </code><p>
        <p><strong>Param:</strong><code> None    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>{
         "triggers":{
            "your-triggers":["Hold","fd"],
            "other-triggers":["Drinking","Driving","Smoking"]
        },
        "emotions":{
            "your-emotions":["Smoke","koi"],
            "other-emotions":["Sad","Happy"]
        },
        "copes":{
            "your-copes":["Smoke","koi"],
            "other-copes":["Sad","Happy"]
        }
}</code></pre>
    </fieldset> -->

    <fieldset class="nl-esi-shadow">
        <legend><h4 class="sec-title">Get Memberships</h4></legend>
        <p><strong>Endpoint:</strong><code> /wp-json/nl-logger/v1/nl-logs/memberships </code><p>
        <p><strong>Method:</strong><code> GET </code><p>
        <p><strong>Param:</strong><code> None    </code><p>
        <p><strong>Response:</strong></p>
        <pre><code>[
         {
            "id":1322,
            "title":"Access to Special Day 12"
         },
         {
            "id":1297,
            "title":"Access to Special Day 11"
         }
]</code></pre>
    </fieldset>





</div>