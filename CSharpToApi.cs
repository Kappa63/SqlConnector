/*
Author: Karim Q.
Date: 8/29/2021
*/
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using System.Net.Http;
using Newtonsoft.Json.Linq;
using Newtonsoft.Json;

namespace TestApp.General
{
    public class SqlConnection
    {
        private string Server;
        private string UserID;
        private string Password;
        private string DB;
        private HttpClient ReqClient;
        public string ResultFormat;
        public SqlConnection(string Server, string UserID, string Password, string DB)
        {
            this.Server = Server;
            this.UserID = UserID;
            this.Password = Password;
            this.DB = DB;
            this.ReqClient = new HttpClient();
            this.ResultFormat = "both";
        }

        public JObject Query(string QueryString, string[] Replacements = null) //returns JObject from Newtonsoft.Json
        {
            FormUrlEncodedContent PostData = new FormUrlEncodedContent(new Dictionary<string, string>{{"Query", QueryString},
                                                                      {"ResFormat", this.ResultFormat}, {"Replace", Replacements != null?JsonConvert.SerializeObject(Replacements):"[]"}});
            Task<HttpResponseMessage> Req = Task.Run(() => ReqClient.PostAsync(String.Format("@URL@/SqlConnector.php?Server={0}&Uid={1}&Pwd={2}&Db={3}", //Url needs to be added
                                                                                              this.Server, this.UserID, this.Password, this.DB), PostData)); Req.Wait();
            Task<string> Res = Task.Run(() => Req.Result.Content.ReadAsStringAsync()); Res.Wait();
            JObject JRes = JObject.Parse(Res.Result);
            return JRes; 
        }
    }
}
