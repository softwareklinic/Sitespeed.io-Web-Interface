
'use strict';

module.exports = {
  run(context) {
    return context.runWithDriver((driver) => {
      return driver.get('https://staging.yourdomain.com/')
        .then(() => {


         // we fetch the selenium webdriver from context
          const webdriver = context.webdriver;
          // and get hold of some goodies we want to use
          const until = webdriver.until;
          const By = webdriver.By;

		driver.findElement(By.name("login")).clear(); 
		driver.findElement(By.name("login")).sendKeys("yourusername"); 
		driver.findElement(By.name("passwd")).clear(); 
		driver.findElement(By.name("passwd")).sendKeys("yourpassword"); 
		driver.findElement(By.id("Log_On")).click(); 

        });
    })
  }
};
