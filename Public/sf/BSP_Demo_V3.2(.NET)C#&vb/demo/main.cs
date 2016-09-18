using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Web;
using System.Threading;

namespace demo
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        #region  声明
        public static int express_type = 0;//产品类型
        /// <summary>
        /// 代收金额
        /// </summary>
        private string COD_value;
        public string COD_value1
        {
            get { return COD_value; }
            set { COD_value = value; }
        }
        /// <summary>
        /// 代收卡号
        /// </summary>
        private string COD_CustID;
        public string COD_CustID1
        {
            get { return COD_CustID; }
            set { COD_CustID = value; }
        }

        public static int COD_flag = 0;//代收货款标志
        public static int INSURE_flag = 0;//保价标志
        /// <summary>
        /// 保价金额
        /// </summary>
        private string INSURE_value;

        public string INSURE_value1
        {
            get { return INSURE_value; }
            set { INSURE_value = value; }
        }

        #endregion

        /// <summary>
        /// 下订单
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void button1_Click(object sender, EventArgs e)
        {
            Cursor.Current = Cursors.WaitCursor;
            #region 清空
            tXTB_Request.Text = null;
            txtb_back.Text = null;
            lab_log.Text = null;
            #endregion
            lab_log.Text = "请求中请稍后。。。。。。";
            //SFService.ServiceClient sf = new SFService.ServiceClient();//V2.8接口
            SFService.ExpressServiceClient sf = new SFService.ExpressServiceClient();//V3.2接口

            string xml = GetWebXml();
            string Checkword =txtB_Checkword.Text.ToString();

            string verifyCode1 = xml + Checkword;
            string verifyCode = MD5Encrypt.MD5ToBase64String(verifyCode1);//生成verifyCode
            tXTB_Request.Text = xml + "   verifyCode:" + verifyCode;//请求报文
            string a = sf.sfexpressService(xml, verifyCode);
            txtb_back.Text = a;
            Thread.Sleep(300);
            lab_log.Text = "请求报文发送成功";
            Cursor.Current = Cursors.Default;
        }

        /// <summary>
        /// 下单报文
        /// </summary>
        /// <returns></returns>
        public string GetWebXml()
        {
            
            StringBuilder strXML = new StringBuilder();
            strXML.Append("<Request service='OrderService' lang='zh-CN'>");//申请服务及语言

            //strXML.Append(GetHead(txtB_JRM.Text.ToString(), txtB_Checkword.Text.ToString())); //客户编码、校验码 V2.8
            strXML.Append(GetHead(txtB_JRM.Text.ToString())); //客户编码 V3.2
            strXML.Append("<Body><Order  "+ GetSendMessage(txtb_j_company.Text.ToString(),txtb_j_contact.Text.ToString(),txtb_j_tel.Text.ToString(),txtb_j_mobile.Text.ToString(),txtb_j_province.Text.ToString (),txtb_j_city.Text.ToString(),txtb_j_county.Text.ToString(),txtb_j_address.Text.ToString ())+"");
            strXML.Append(" "+ GetReceiveMessage(txtb_d_company.Text.ToString(),txtb_d_contact.Text.ToString(),txtb_d_tel.Text.ToString(),txtb_d_mobile.Text.ToString(),txtb_d_province.Text.ToString (),txtb_d_city.Text .ToString (),txtb_d_county.Text.ToString (),txtb_d_address.Text.ToString ()));
            strXML.Append(" "+GetOrderMessage(txtb_orderid.Text.ToString(),express_type,pay_method,txtb_parcel_quantity.Text.ToString(),txtb_custid.Text.ToString(),txtb_cargo_total_weight.Text.ToString(),dtp_sendstarttime.Text.ToString (),txtb_order_source.Text.ToString(),txtb_remark.Text .ToString())+">");
            strXML.Append(GetCargoMessage(txtb_CargoName.Text.ToString(),txtb_count.Text.ToString(),txtb_unit.Text.ToString(),txtb_weight.Text.ToString(),txtb_amount.Text.ToString(),txtb_currency.Text.ToString(),txtb_source_area.Text.ToString()));
            #region 代收货款
            if (COD_flag == 1)
            {
               strXML.Append("<AddedService name='COD' value='"+txtb_cod_value.Text.ToString()+"' value1='"+txtb_cod_custid.Text.ToString()+"' /> ");
            }
            #endregion
            #region  保价
            if (INSURE_flag == 1)
            {
                strXML.Append(" <AddedService name='INSURE' value='"+txtb_INSURE_value.Text.ToString()+"' />"); 
            }
            #endregion
               
            strXML.Append("</Order></Body></Request>");
            return strXML.ToString();

        }

        /// <summary>
        /// 获取头文件（WebService）
        /// </summary>
        /// <param name="ClientNo">接口编码或客户编码</param>
        /// <param name="Checkword">校验码</param>
        /// <returns>报头文件</returns>
        public string GetHead(string ClientNo)
        {
            StringBuilder strXML = new StringBuilder();
            strXML.Append("<Head>" + ClientNo + "</Head>");
            return strXML.ToString();

        }

        /// <summary>
        /// 获取货品信息
        /// </summary>
        /// <param name="CargoName">商品名称</param>
        /// <param name="count">商品数量</param>
        /// <param name="unit">单位</param>
        /// <param name="weight">单重</param>
        /// <param name="amount">单价</param>
        /// <param name="currency">结算货币（国际件用）</param>
        /// <param name="source_area">原产地（国际件用）</param>
        /// <returns>货品信息</returns>
        public string  GetCargoMessage(string CargoName,string count,string unit,string weight,string amount,string currency,string source_area)
        {
            StringBuilder strXML = new StringBuilder();
            strXML.Append(" <Cargo  " );
            strXML.Append(" Name='" + CargoName + "' ");
            strXML.Append("count='" + count + "' ");
            strXML.Append("unit='" + unit + "' ");
            strXML.Append("weight='" + weight + "' ");
            strXML.Append("amount='" + amount + "' ");
            strXML.Append("currency='" + currency + "' ");
            strXML.Append("source_area='" + source_area + "' ");
            strXML.Append("  ></Cargo> ");
            
            return strXML.ToString();
        }

        /// <summary>
        /// 获取寄件信息
        /// </summary>
        /// <param name="j_company">寄件公司</param>
        /// <param name="j_contact">联系人</param>
        /// <param name="j_tel">联系电话</param>
        /// <param name="j_mobile">联系手机</param>
        /// <param name="j_province">省</param>
        /// <param name="j_city">市</param>
        /// <param name="j_county">区/县</param>
        /// <param name="j_address">详细地址</param>
        /// <returns>寄件信息</returns>
        public string GetSendMessage(string j_company, string j_contact, string j_tel, string j_mobile, string j_province, string j_city, string j_county, string j_address)
        {
            StringBuilder strXML = new StringBuilder();
            strXML.Append(" j_company='" + j_company + "' ");
            strXML.Append("j_contact='" + j_contact + "' ");
            strXML.Append("j_tel='" + j_tel + "' ");
            strXML.Append("j_mobile='" + j_mobile + "' ");
            strXML.Append("j_province='" + j_province + "' ");
            strXML.Append("j_city='" + j_city + "' ");
            strXML.Append("j_county='" + j_county + "' ");
            strXML.Append("j_address='" + j_address + "' ");
            return strXML.ToString();

        }

        /// <summary>
        /// 获取收件信息
        /// </summary>
        /// <param name="d_company">收件公司</param>
        /// <param name="d_contact">联系人</param>
        /// <param name="d_tel">联系电话</param>
        /// <param name="d_mobile">手机号码</param>
        /// <param name="d_province">省</param>
        /// <param name="d_city">市</param>
        /// <param name="d_county">区/县</param>
        /// <param name="d_address">详细地址</param>
        /// <returns>收件信息</returns>
        public string GetReceiveMessage(string d_company, string d_contact, string d_tel, string d_mobile, string d_province, string d_city, string d_county, string d_address)
        {
            StringBuilder strXML = new StringBuilder();
            strXML.Append(" d_company='" + d_company + "' ");
            strXML.Append("d_contact='" + d_contact + "' ");
            strXML.Append("d_tel='" + d_tel + "' ");
            strXML.Append("d_mobile='" + d_mobile + "' ");
            strXML.Append("d_province='" + d_province + "' ");
            strXML.Append("d_city='" + d_city + "' ");
            strXML.Append("d_county='" + d_county + "' ");
            strXML.Append("d_address='" + d_address + "' ");
            return strXML.ToString();

        }
         
        /// <summary>
        /// 窗体加载
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void Form1_Load(object sender, EventArgs e)
        { 
            #region  业务类型加载

            comb_express_type.Items.Add("--选择业务类型--");
            comb_express_type.Items.Add("标准快递");
            comb_express_type.Items.Add("顺丰特惠");
            comb_express_type.Items.Add("电商特惠");
            comb_express_type.Items.Add("顺丰次晨");
            comb_express_type.Items.Add("即日件");
            comb_express_type.Items.Add("电商速配");
            comb_express_type.Items.Add("顺丰宝平邮");
            comb_express_type.Items.Add("顺丰宝挂号");
            comb_express_type.Items.Add("医药常温");
            comb_express_type.Items.Add("医药温控");
            comb_express_type.Items.Add("物流普运");
            comb_express_type.Items.Add("冷运宅配");
            comb_express_type.Items.Add("生鲜速配");
            comb_express_type.Items.Add("大闸蟹专递");
            comb_express_type.Items.Add("汽配专线");
            comb_express_type.Items.Add("汽配吉运");
            comb_express_type.Items.Add("全球顺");
            comb_express_type.Items.Add("行邮专列");
            comb_express_type.Items.Add("医药专运（常温）");
            comb_express_type.Items.Add("医药专运（温控）");
            


            #endregion

            #region 付款方式
            comb_pay_method.Items.Add("--选择业务类型--");
            comb_pay_method.Items.Add("寄付");
            comb_pay_method.Items.Add("到付");
            comb_pay_method.Items.Add("第三方付");

            
            #endregion
            comb_express_type.SelectedIndex = 0;
            comb_pay_method.SelectedIndex = 0;
        }
         
        #region 业务类型选择
        
        private void comb_express_type_SelectedIndexChanged(object sender, EventArgs e)
        {
            switch (comb_express_type.SelectedIndex)
            {
                #region express_type赋值
                case 1:
                express_type= 1;
                break;
                case 2:
                express_type = 2;
                break;
                case 3:
                express_type = 3;
                break;
                case 4:
                express_type = 5;
                break;
                case 5:
                express_type = 6;
                break;
                case 6:
                express_type = 7;
                break;
                case 7:
                express_type = 9;
                break;
                case 8:
                express_type = 10;
                break;
                case 9:
                express_type = 11;
                break;
                case 10:
                express_type = 12;
                break;
                case 11:
                express_type = 13;
                break;
                case 12:
                express_type = 14;
                break;
                case 13:
                express_type = 15;
                break;
                case 14:
                express_type = 16;
                break;
                case 15:
                express_type = 17;
                break;
                case 16:
                express_type = 18;
                break;
                case 17:
                express_type = 19;
                break;
                case 18:
                express_type = 20;
                break;
                case 19:
                express_type = 21;
                break;
                case 20:
                express_type = 22;
                break;
                
                
                #endregion
            }
        }

        #endregion
         
        #region 付款方式选择
        public static int pay_method = 0;
        
        

        private void comb_pay_method_SelectedIndexChanged(object sender, EventArgs e)
        {
            switch (comb_pay_method.SelectedIndex)
            {
                #region pay_method赋值
                case 1:
                    pay_method = 1;
                    break;
                case 2:
                    pay_method = 2;
                    break;
                case 3:
                    pay_method = 3;
                    break;

                #endregion
            }
        }
        #endregion


        /// <summary>
        /// 获取订单信息
        /// </summary>
        /// <param name="orderid">订单号</param>
        /// <param name="express_type">业务类型</param>
        /// <param name="pay_method">支付方式</param>
        /// <param name="parcel_quantity">包裹数</param>
        /// <param name="custid">月结卡号</param>
        /// <param name="cargo_total_weight">快件总重量</param>
        /// <param name="sendstarttime">发货时间</param>
        /// <param name="order_source">订单来源</param>
        /// <param name="remark">备注信息</param>
        /// <returns>获取订单信息</returns>
        public string GetOrderMessage(string orderid, int express_type, int pay_method, string parcel_quantity, string custid, string cargo_total_weight, string sendstarttime, string order_source, string remark)
        { 
            StringBuilder strXML = new StringBuilder();
            strXML.Append("orderid ='" + orderid + "' ");
            strXML.Append("express_type ='" + express_type.ToString() + "' ");
            strXML.Append("pay_method ='" + pay_method.ToString() + "' ");
            strXML.Append("parcel_quantity ='" + parcel_quantity + "' ");
            strXML.Append("custid ='" + custid + "' ");
            strXML.Append("cargo_total_weight ='" + cargo_total_weight + "' ");
            strXML.Append("sendstarttime ='" + sendstarttime + "' ");
            strXML.Append("order_source ='" + order_source + "' ");
            strXML.Append("remark ='" + remark + "' ");
            

            return strXML.ToString();
        }
/// <summary>
/// 路由查询
/// </summary>
/// <param name="sender"></param>
/// <param name="e"></param>
        private void button2_Click(object sender, EventArgs e)
        {
            Cursor.Current = Cursors.WaitCursor;
            #region 清空
            tXTB_Request.Text = null;
            txtb_back.Text = null;
            lab_log.Text = null;
            #endregion
            lab_log.Text = "请求中请稍后。。。。。。";
            //SFService.ServiceClient sf = new SFService.ServiceClient();//V2.8接口
            SFService.ExpressServiceClient sf = new SFService.ExpressServiceClient();//V3.2接口

            string xml = GetRouteXml();
            string Checkword = txtB_Checkword.Text.ToString();

            string verifyCode1 = xml + Checkword;
            string verifyCode = MD5Encrypt.MD5ToBase64String(verifyCode1);//生成verifyCode
            tXTB_Request.Text = xml + "   verifyCode:" + verifyCode;//请求报文
            string a = sf.sfexpressService(xml, verifyCode);
            txtb_back.Text = a;
            Thread.Sleep(300);
            lab_log.Text = "请求报文发送成功";
            Cursor.Current = Cursors.Default;
        }

       

        /// <summary>
        /// COD
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void checkB_COD_CheckedChanged(object sender, EventArgs e)
        {
            if (checkB_COD.Checked)
            {
                #region 显示
                label39.Visible = true;
                label40.Visible = true;
                txtb_cod_custid.Visible = true;
                txtb_cod_value.Visible = true;
                label42.Visible = true;
                label43.Visible = true;
                #endregion
                #region 赋值
              
                COD_flag = 1;//1有COD 0无COD

                #endregion
            }
            else
            {
                #region 显示
                label39.Visible = false;
                label40.Visible = false;
                txtb_cod_custid.Visible = false;
                txtb_cod_value.Visible = false;
                label42.Visible = false;
                label43.Visible = false;
                #endregion
                #region 赋值
                COD_value = null;
                COD_CustID = null;
                COD_flag = 0;
                #endregion
            }
        }

        
        /// <summary>
        /// 保价
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void checkB_INSURE_CheckedChanged(object sender, EventArgs e)
        {
            if (checkB_INSURE.Checked)
            {
                #region 显示
                label41.Visible = true;
                label44.Visible = true;
                txtb_INSURE_value.Visible = true;

                #endregion
                #region 赋值
               
                INSURE_flag = 1;
                #endregion
            }
            else
            {

                #region 显示
                label41.Visible = false;
                label44.Visible = false;
                txtb_INSURE_value.Visible = false;
                #endregion
                #region 赋值
                INSURE_value = null;
                INSURE_flag = 0;
                #endregion
            }
            

        }


         ///<summary>
         ///路由查询报文
         ///</summary>
         ///<returns></returns>
        public string GetRouteXml()
        {

            StringBuilder strXML = new StringBuilder();
            strXML.Append("<Request service='RouteService' lang='zh-CN'>");//申请服务及语言
            strXML.Append(GetHead(txtB_JRM.Text.ToString())); //客户编码 V3.2
            strXML.Append("<Body><RouteRequest tracking_type='1'  method_type= '1'  tracking_number='755123456789'/>");
            strXML.Append("</RouteRequest></Body></Request>");
            return strXML.ToString();

        }

      
    }
}
