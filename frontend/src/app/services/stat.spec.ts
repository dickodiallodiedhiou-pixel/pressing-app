import { TestBed } from '@angular/core/testing';

import { Stat } from './stat';

describe('Stat', () => {
  let service: Stat;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(Stat);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
