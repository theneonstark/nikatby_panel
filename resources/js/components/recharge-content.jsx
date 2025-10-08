"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Smartphone, Tv, Wifi, Zap } from "lucide-react"
import { doRecharge } from "@/lib/apis"
import { useState } from "react"

// const rechargeTypes = [
//   { icon: Smartphone, title: "Mobile Recharge", description: "Prepaid mobile recharge" },
//   { icon: Tv, title: "DTH Recharge", description: "Direct-to-home TV recharge" },
//   { icon: Wifi, title: "Data Card", description: "Internet data card recharge" },
//   { icon: Zap, title: "Postpaid", description: "Postpaid bill payment" },
// ]

const recentRecharges = [
  { number: "9876543210", operator: "Airtel", amount: "$25", status: "Success", date: "2024-01-15" },
  { number: "9876543211", operator: "Jio", amount: "$30", status: "Success", date: "2024-01-14" },
  { number: "9876543212", operator: "Vi", amount: "$20", status: "Failed", date: "2024-01-13" },
]

export function RechargeContent({operators}) {
  const [mobile, setMobile] = useState("");
  const [operator, setOperator] = useState("");
  const [amount, setAmount] = useState("");
  const [loading, setLoading] = useState(false);

  const handleRecharge = async () => {
    if (!mobile || !operator || !amount) {
      alert("Please fill all fields");
      return;
    }

    try {
      setLoading(true);
      const response = await doRecharge({
        canumber: mobile,
        operator: operator,
        amount: amount,
      })
      console.log("Recharge Response:", response.data);
      alert("Recharge request sent successfully!");
    } catch (error) {
      console.error("Recharge error:", error);
      alert("Something went wrong while processing your recharge.");
    } finally {
      setLoading(false);
    }
  };
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">Recharge Services</h1>
        <p className="text-muted-foreground">Mobile</p>
      </div>

      {/* <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {rechargeTypes.map((type, index) => (
          <motion.div
            key={type.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
          >
            <Card className="hover:shadow-lg transition-shadow cursor-pointer">
              <CardHeader className="text-center">
                <type.icon className="w-12 h-12 text-primary mx-auto mb-2" />
                <CardTitle className="text-lg">{type.title}</CardTitle>
                <CardDescription>{type.description}</CardDescription>
              </CardHeader>
            </Card>
          </motion.div>
        ))}
      </div> */}

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Quick Recharge</CardTitle>
            <CardDescription>Recharge mobile</CardDescription>
          </CardHeader>
          <CardContent>
            <Tabs defaultValue="mobile" className="w-full">
              {/* <TabsList className="grid w-full grid-cols-3">
                <TabsTrigger value="mobile">Mobile</TabsTrigger>
                <TabsTrigger value="dth">DTH</TabsTrigger>
                <TabsTrigger value="data">Data Card</TabsTrigger>
              </TabsList> */}
              <TabsContent value="mobile" className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="mobile">Mobile Number</Label>
                  <Input
                    id="mobile"
                    placeholder="Enter mobile number"
                    value={mobile}
                    onChange={(e) => setMobile(e.target.value)}
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="operator">Operator</Label>
                  <Select onValueChange={(value) => setOperator(value)}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select operator" />
                    </SelectTrigger>
                    <SelectContent>
                      {operators && operators.length > 0 ? (
                        operators.map((op) => (
                          <SelectItem key={op.id} value={op.id}>
                            {op.name}
                          </SelectItem>
                        ))
                      ) : (
                        <div className="text-center py-2 text-sm text-muted-foreground">
                          No operators found
                        </div>
                      )}
                    </SelectContent>
                  </Select>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="dth-amount">Amount</Label>
                  <Input
                    id="dth-amount"
                    placeholder="Enter amount"
                    value={amount}
                    onChange={(e) => setAmount(e.target.value)}
                  />
                </div>

                <Button className="w-full" onClick={handleRecharge} disabled={loading}>
                  {loading ? "Processing..." : "Recharge Now"}
                </Button>
              </TabsContent>
              {/* <TabsContent value="dth" className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="subscriber">Subscriber ID</Label>
                  <Input id="subscriber" placeholder="Enter subscriber ID" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="dth-operator">DTH Operator</Label>
                  <Select>
                    <SelectTrigger>
                      <SelectValue placeholder="Select DTH operator" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="tata-sky">Tata Sky</SelectItem>
                      <SelectItem value="dish-tv">Dish TV</SelectItem>
                      <SelectItem value="airtel-digital">Airtel Digital TV</SelectItem>
                      <SelectItem value="sun-direct">Sun Direct</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="dth-amount">Amount</Label>
                  <Input id="dth-amount" placeholder="Enter amount" />
                </div>
                <Button className="w-full">Recharge Now</Button>
              </TabsContent> */}
              {/* <TabsContent value="data" className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="data-number">Data Card Number</Label>
                  <Input id="data-number" placeholder="Enter data card number" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="data-operator">Operator</Label>
                  <Select>
                    <SelectTrigger>
                      <SelectValue placeholder="Select operator" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="airtel">Airtel</SelectItem>
                      <SelectItem value="jio">Jio</SelectItem>
                      <SelectItem value="vi">Vi</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="data-amount">Amount</Label>
                  <Input id="data-amount" placeholder="Enter amount" />
                </div>
                <Button className="w-full">Recharge Now</Button>
              </TabsContent> */}
            </Tabs>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Recharges</CardTitle>
            <CardDescription>Your recent recharge transactions</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentRecharges.map((recharge, index) => (
                <div key={index} className="flex items-center justify-between p-3 border rounded-lg">
                  <div>
                    <p className="font-medium">{recharge.number}</p>
                    <p className="text-sm text-muted-foreground">
                      {recharge.operator} • {recharge.date}
                    </p>
                  </div>
                  <div className="text-right">
                    <p className="font-medium">{recharge.amount}</p>
                    <p className={`text-sm ${recharge.status === "Success" ? "text-green-600" : "text-red-600"}`}>
                      {recharge.status}
                    </p>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </motion.div>
  )
}
