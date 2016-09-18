using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Web;

namespace demo
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            SFService.ServiceClient sf = new SFService.ServiceClient();
            string xml = GetWebXml();
            tXTB_Request.Text = xml;//请求报文

           string a = sf.sfexpressService(xml);
           txtb_back.Text = a;
        }

        public string GetWebXml()
        {
            StringBuilder strXML = new StringBuilder();
            strXML.Append("<Request service='OrderService' lang='zh-CN'>");//申请服务及语言
          
            strXML.Append(GetHead(txtB_JRM.Text.ToString(), txtB_Checkword.Text.ToString())); //客户编码、校验码
            strXML.Append("<Body><Order orderid='XJFS_07110003' express_type='1' j_company='xx公司' j_contact='客服'  j_tel='025-10106699' j_mobile='13800138000'  j_province='北京'  j_city='北京市'  j_county='海淀区'  j_address='北京市海淀区科学园科健路328号'");
            strXML.Append(" d_company='顺丰速运'  d_contact='小顺'  d_tel='0755-33992159'  d_mobile='15602930913'  d_province='广东省' d_city='深圳市'  d_county='福田区'  d_address='广东省深圳市福田区新洲十一街万基商务大厦10楼' ");
            strXML.Append(" parcel_quantity='1'  pay_method='1'  custid='7551878519'  cargo_total_weight='2.18'  sendstarttime='2014-07-11 12:07:11'  remark=''  order_source='订单来至西门俊宇' >");
            strXML.Append(" <Cargo name='服装' count='1' unit='台' weight='2.36' amount='2000' currency='CNY' source_area='中国'></Cargo>");
     
       //strXML.Append("<AddedService name='COD' value='2000' value1='7551878519' /> ");
       // strXML.Append(" <AddedService name='INSURE' value='2000' />");      
             strXML.Append("</Order></Body></Request>");
            return strXML.ToString();




        }

        /// <summary>
        /// 获取头文件（WebService）
        /// </summary>
        /// <param name="ClientNo">接口编码或客户编码</param>
        /// <param name="Checkword">校验码</param>
        /// <returns>报头文件</returns>
        public string GetHead(string ClientNo, string Checkword)
        {
            StringBuilder strXML = new StringBuilder();
            strXML.Append("<Head>" + ClientNo + "," + Checkword + "</Head>");
            return strXML.ToString();

        }
       
        public string Get
    }

        private void txtb_back_TextChanged()
        {
        
        }
}
