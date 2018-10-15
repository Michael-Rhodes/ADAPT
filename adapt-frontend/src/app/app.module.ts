import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { LogTableComponent } from './log-table/log-table.component';
import { LogTableRowComponent } from './log-table-row/log-table-row.component';

@NgModule({
  declarations: [
    AppComponent,
    LogTableComponent,
    LogTableRowComponent
  ],
  imports: [
    BrowserModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
