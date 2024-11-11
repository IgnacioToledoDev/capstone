import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LisquoticliPage } from './lisquoticli.page';

describe('LisquoticliPage', () => {
  let component: LisquoticliPage;
  let fixture: ComponentFixture<LisquoticliPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LisquoticliPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
